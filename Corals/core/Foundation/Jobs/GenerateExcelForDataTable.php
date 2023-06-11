<?php

namespace Corals\Foundation\Jobs;

use Corals\Foundation\Classes\ExcelWriter;
use Corals\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Transformers\DataArrayTransformer;

class GenerateExcelForDataTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dataTable;
    protected $scopes;
    protected $columns;
    protected $user;
    protected $tableID;
    protected $download;


    /**
     * GenerateExcelForDataTable constructor.
     * @param $dataTable
     * @param $scopes
     * @param $columns
     * @param $tableID
     * @param User $user
     * @param false $download
     */
    public function __construct($dataTable, $scopes, $columns, $tableID, User $user, $download = false)
    {
        $this->dataTable = $dataTable;
        $this->scopes = $scopes;
        $this->columns = $columns;
        $this->user = $user;
        $this->tableID = str_replace('DataTable', '', $tableID);
        $this->download = $download;
    }

    /**
     * Execute the job.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function handle()
    {
        try {
            logger('start exporting: ' . $this->dataTable);

            $dataTable = app()->make($this->dataTable);

            $query = app()->call([$dataTable, 'query']);

            $dt = new EloquentDataTable($query);

            $source = $dt->getFilteredQuery();

            $modelTransformer = $this->getModelTransformer($dataTable, $source);

            $transformer = new DataArrayTransformer();

            //apply scopes
            foreach ($this->scopes as $scope) {
                $scope->apply($source);
            }

            $rootPath = config('app.export_excel_base_path');

            $exportName = join('_', [
                'table_' . $this->tableID,
                'user_id_' . $this->user->id,
                str_replace(['-', ':', ' '], '_', now()->toDateTimeString()) . '.xlsx'
            ]);

            $filePath = storage_path($rootPath . $exportName);

            if (!file_exists($rootPath = storage_path($rootPath))) {
                mkdir($rootPath, 0755, true);
            }

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $writer = ExcelWriter::create($filePath);

            $source->chunk(100, function ($data) use ($transformer, $writer, $modelTransformer) {
                foreach ($data as $row) {
                    $row = $modelTransformer->transform($row);

                    $rowData = $transformer->transform($row, $this->columns, 'exportable');

                    $writer->addRow($rowData);
                }
            });

            $writer->close();

            if ($this->download) {
                logger($exportName . ' Completed');
                return response()->download($filePath);
            }

            event('notifications.user.send_excel_file', [
                'file' => $filePath,
                'user' => $this->user,
                'table_id' => $this->tableID
            ]);

            logger($exportName . ' Completed');
        } catch (\Exception $exception) {
            report($exception);
        }
    }

    /**
     * @param $dataTable
     * @param $source
     * @return mixed
     */
    protected function getModelTransformer($dataTable, $source)
    {
        return Arr::first($dataTable->dataTable($source)->transformer);
    }

}


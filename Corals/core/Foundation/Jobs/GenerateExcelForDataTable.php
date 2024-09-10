<?php

namespace Corals\Foundation\Jobs;

use Corals\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use League\Fractal\TransformerAbstract;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Transformers\DataArrayTransformer;
use Illuminate\Contracts\Database\Query\Builder as BuilderContract;

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
     * @var int
     */
    protected int $chunkSize = 200;

    /**
     * @var int|mixed
     */
    protected int $currentPage;

    /**
     * @var string|mixed|null
     */
    protected string|null $appendToFilePath;

    /**
     * @var bool
     */
    protected bool $headersProcessed = false;

    /**
     * GenerateExcelForDataTable constructor.
     * @param $dataTable
     * @param $scopes
     * @param $columns
     * @param $tableID
     * @param User $user
     * @param false $download
     * @param int $currentPage
     * @param null $appendToFilePath
     */
    public function __construct(
        $dataTable,
        $scopes,
        $columns,
        $tableID,
        User $user,
        $download = false,
        $currentPage = 1,
        $appendToFilePath = null
    )
    {
        $this->dataTable = $dataTable;
        $this->scopes = $scopes;
        $this->columns = $columns;
        $this->user = $user;
        $this->tableID = str_replace('DataTable', '', $tableID);
        $this->download = $download;
        $this->currentPage = $currentPage;
        $this->appendToFilePath = $appendToFilePath;
    }

    /**
     * Execute the job.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function handle()
    {
        try {

            $this->login();

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

            $this->appendToFilePath = $this->generateFilePath();

            $writer = Writer::createFromPath($this->appendToFilePath, 'a+')
                ->setDelimiter(config('corals.csv_delimiter', ','));

            $source = $this->write(
                $writer, $source, $transformer, $modelTransformer
            );

            if ($this->download) {
                logger($this->appendToFilePath . ' Completed');
                $this->logout();
                return response()->download($this->appendToFilePath);
            }

            /**
             * @var $source LengthAwarePaginator
             */
            if ($source->hasMorePages()) {
                $this->pushNextJob();
            } else {
                event('notifications.user.send_excel_file', [
                    'file' => $this->appendToFilePath,
                    'user' => $this->user,
                    'table_id' => $this->tableID
                ]);
            }

            $this->logout();

            logger($this->appendToFilePath . ' Completed');
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
        return Arr::first($dataTable->dataTable($source)->getTransformer());
    }

    /**
     * @param DataArrayTransformer $transformer
     * @param Writer $writer
     * @param TransformerAbstract $modelTransformer
     * @return callable
     */
    protected function exportItemsCallback(
        DataArrayTransformer $transformer,
        Writer $writer,
        TransformerAbstract $modelTransformer
    ): callable
    {
        return function ($data) use ($transformer, $writer, $modelTransformer) {

            foreach ($data as $row) {
                $row = $modelTransformer->transform($row);

                $rowData = $transformer->transform($row, $this->columns, 'exportable');

                if ($this->currentPage === 1 && !$this->headersProcessed) {
                    $writer->insertOne(array_keys($rowData));
                    $this->headersProcessed = true;
                }

                $writer->insertOne($rowData);
            }
        };
    }

    /**
     *
     */
    protected function pushNextJob(): void
    {
        self::dispatch(
            $this->dataTable,
            $this->scopes,
            $this->columns,
            $this->tableID,
            $this->user,
            $this->download,
            $this->currentPage + 1,
            $this->appendToFilePath
        );
    }

    /**
     * @param Writer $writer
     * @param BuilderContract $source
     * @param DataArrayTransformer $transformer
     * @param TransformerAbstract $modelTransformer
     * @return bool|LengthAwarePaginator
     */
    protected function write(
        Writer $writer,
        BuilderContract $source,
        DataArrayTransformer $transformer,
        TransformerAbstract $modelTransformer
    ): LengthAwarePaginator|bool
    {
        return $this->download
            ? $source->chunk($this->chunkSize, $this->exportItemsCallback($transformer, $writer, $modelTransformer))
            : $this->paginate($writer, $source, $transformer, $modelTransformer);
    }

    /**
     * @param Writer $writer
     * @param BuilderContract $source
     * @param DataArrayTransformer $transformer
     * @param TransformerAbstract $modelTransformer
     * @return LengthAwarePaginator
     */
    protected function paginate(
        Writer $writer,
        BuilderContract $source,
        DataArrayTransformer $transformer,
        TransformerAbstract $modelTransformer
    ): LengthAwarePaginator
    {
        $source = $source->paginate(perPage: $this->chunkSize, page: $this->currentPage);

        /**
         * @var $source LengthAwarePaginator
         */
        $source->pipe($this->exportItemsCallback($transformer, $writer, $modelTransformer));

        return $source;
    }

    /**
     * @return mixed|string|null
     */
    protected function generateFilePath()
    {
        if ($this->appendToFilePath) {
            return $this->appendToFilePath;
        }

        $exportName = join('_', [
            'table_' . $this->tableID,
            'user_id_' . $this->user->id,
            str_replace(['-', ':', ' '], '_', now()->toDateTimeString()) . '.csv'
        ]);

        $rootPath = config('app.export_excel_base_path');

        $filePath = storage_path($rootPath . $exportName);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        if (!file_exists($rootPath = storage_path($rootPath))) {
            mkdir($rootPath, 0755, true);
        }

        return $filePath;
    }

    /**
     *
     */
    protected function login(): void
    {
        if (!app()->runningInConsole()) {
            return;
        }

        $userId = data_get(optional($this->job)->payload(), 'user_id');

        if ($userId) {
            Auth::loginUsingId($userId);
        }
    }

    /**
     *
     */
    protected function logout(): void
    {
        if (!app()->runningInConsole()) {
            return;
        }

        if (Auth::user()) {
            Auth::logout();
        }
    }
}


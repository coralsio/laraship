<?php


namespace Corals\Foundation\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception as CSVException;
use League\Csv\Reader;
use League\Csv\Writer;

trait ImportTrait
{
    /**
     * @var Writer
     */
    protected $importLogWriter;
    /**
     * @var string
     */
    protected $importLogFile;
    protected $success_records_count = 0;
    protected $failed_records_count = 0;

    /**
     * @throws CSVException
     */
    protected function doImport()
    {
        $this->initHandler();

        $reader = Reader::createFromPath($this->importFilePath, 'r')
            ->setDelimiter(config('corals.csv_delimiter', ','))
            ->setHeaderOffset(0);


        foreach ($reader->getRecords() as $record) {
            DB::beginTransaction();
            try {
                $this->handleImportRecord($record);
                $this->success_records_count++;
            } catch (\Exception $exception) {
                $this->failed_records_count++;
                $this->logRecordException($record, $exception->getMessage());
            }
            DB::commit();
        }

        //send notification
        event('notifications.import_status', [
            'user' => $this->user,
            'import_file_name' => basename($this->importFilePath),
            'import_log_file' => $this->importLogWriter ? HtmlElement('a',
                ['href' => asset($this->importLogFile), 'target' => '_blank'],
                basename($this->importLogFile)) : '-',
            'success_records_count' => $this->success_records_count,
            'failed_records_count' => $this->failed_records_count,
        ]);
    }

    protected abstract function initHandler();

    protected abstract function getValidationRules($data): array;

    /**
     * @param array $data
     * @throws \Exception
     */
    protected function validateRecord(array $data)
    {
        $rules = $this->getValidationRules($data);

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new \Exception(json_encode($validator->errors()->jsonSerialize()));
        }
    }

    /**
     * @param $record
     * @param $message
     * @throws CannotInsertRecord
     */
    protected function logRecordException($record, $message)
    {
        if (!$this->importLogWriter) {
            //we create the CSV into memory
            $logName = basename($this->importFilePath, '.csv') . Str::random(10);

            $logBasePath = 'imports';

            $this->importLogFile = "$logBasePath/$logName.csv";

            if (!File::exists(public_path($logBasePath))) {
                File::makeDirectory(public_path($logBasePath), 0755, true);
            }

            $this->importLogWriter = Writer::createFromPath(public_path($this->importLogFile), 'w+')
                ->setDelimiter(config('corals.csv_delimiter', ','));

            $headers = $this->importHeaders;
            $headers[] = 'Import Message';

            //we insert the CSV header
            $this->importLogWriter->insertOne($headers);
        }

        $record['Import Message'] = $message;

        $this->importLogWriter->insertOne($record);
    }

    /**
     * @param $filePath
     * @return string
     */
    protected function getFilePath($filePath)
    {
        return base_path(trim(
            join('', [
                DIRECTORY_SEPARATOR,
                trim($filePath, '/\\ ')
            ])
            , '/\\ '));
    }
}

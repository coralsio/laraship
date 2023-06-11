<?php


namespace Corals\Activity\HttpLogger\Jobs;


use Carbon\Carbon;
use Corals\Activity\HttpLogger\Models\HttpLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PurgeHttpLogTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        try {
            logger('Purge Http Logger');

            $this->dumpHttpLogTable();

            $maxAgeInDays = config('http_logger.delete_records_older_than_days', 15);

            $cutOffDate = Carbon::now()->subDays($maxAgeInDays)->format('Y-m-d H:i:s');

            $totalDeleted = 0;

            $maxTries = 10000;

            do {
                $amountDeleted = HttpLog::where('created_at', '<', $cutOffDate)
                    ->take(1000)
                    ->delete();

                $totalDeleted += $amountDeleted;

                logger('Partial Purge: ' . $amountDeleted);

                if ($maxTries === 0) {
                    logger('Reached Max Tries');
                    break;
                }

                $maxTries--;
            } while ($amountDeleted > 0);

            logger('Total Purged: ' . $totalDeleted);
            logger('Purge Http Logger Completed');
        } catch (\Exception $exception) {
            report($exception);
        }
    }

    protected function dumpHttpLogTable()
    {
        logger('start http_log dump');

        $dbConfig = config('database.connections')[config('database.default')];

        $table = 'http_log';

        if (!file_exists(storage_path('backups'))) {
            mkdir(storage_path('backups'), 0755);
        }

        $destinationFile = storage_path(sprintf('backups/%s_%s.sql', $table, now()->format('Y-m-d-h-i')));

        $command = sprintf('mysqldump --user=%s --password=%s --host=%s --port=%s %s %s > %s',
            escapeshellarg(data_get($dbConfig, 'username')),
            escapeshellarg(data_get($dbConfig, 'password')),
            escapeshellarg(data_get($dbConfig, 'host')),
            escapeshellarg(data_get($dbConfig, 'port')),
            escapeshellarg(data_get($dbConfig, 'database')),
            escapeshellarg($table),
            escapeshellarg($destinationFile)
        );

        $result = $this->runInConsole($command);

        logger('dump command result: ' . ($result ?: 'null or false'));

        logger('dump file:' . $destinationFile);
    }

    protected function runInConsole($command)
    {
        return shell_exec($command);
    }
}

<?php

namespace Corals\Foundation\Classes\Installation;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ConfigureDatabase
{
    /**
     * @var Filesystem
     */
    private $finder;

    /**
     * @var string
     */
    protected $template = '.env.example';

    /**
     * @var string
     */
    protected $file = '.env';

    /**
     * ConfigureDatabase constructor.
     * @param Filesystem $finder
     */
    public function __construct(Filesystem $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @var Command
     */
    protected $command;

    /**
     * @param Command $command
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function fire(Command $command)
    {
        $this->command = $command;

        $connected = false;

        while (!$connected) {
            $host = $this->askDatabaseHost();
            $database = $this->askDatabaseName();
            $username = $this->askDatabaseUsername();
            $password = $this->askDatabasePassword();

            if ($this->databaseConnectionIsValid($host, $username, $password, $database)) {
                config(['database.connections.mysql.host' => $host]);
                config(['database.connections.mysql.username' => $username]);
                config(['database.connections.mysql.password' => $password]);
                config(['database.connections.mysql.database' => $database]);
                $connected = true;
            } else {
                $command->error('Please ensure your database credentials are valid.');
            }
        }

        $this->write($database, $username, $password, $host);

        $command->info('Database successfully configured.');
    }

    /**
     * @param $database
     * @param $username
     * @param $password
     * @param $host
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function write($database, $username, $password, $host)
    {
        try {
            $environmentFile = $this->finder->get($this->file);
        } catch (\Exception $exception) {
            $environmentFile = $this->finder->get($this->template);
        }

        $envArray = array_filter(preg_split('/\r\n|\r|\n/', $environmentFile));

        $search = [];

        foreach ($envArray as $value) {
            if (Str::startsWith($value, 'DB_')) {
                $search[] = $value;
            }
        }

        $replace = [
            "DB_CONNECTION=mysql",
            "DB_HOST=$host",
            "DB_PORT=3306",
            "DB_DATABASE=$database",
            "DB_USERNAME=$username",
            "DB_PASSWORD=$password",
        ];

        $newEnvironmentFile = str_replace($search, $replace, $environmentFile);

        $this->finder->put($this->file, $newEnvironmentFile);
    }

    /**
     * @return string
     */
    protected function askDatabaseHost()
    {
        $host = $this->command->ask('Enter your database host.', env('DB_HOST', '127.0.0.1'));

        return $host;
    }

    /**
     * @return string
     */
    protected function askDatabaseName()
    {
        $database = $this->command->ask('Enter your database name.', env('DB_DATABASE', 'homestead'));

        return $database;
    }

    /**
     * @param
     *
     * @return string
     */
    protected function askDatabaseUsername()
    {
        $username = $this->command->ask('Enter your database username.', env('DB_USERNAME', 'homestead'));

        return $username;
    }

    /**
     * @param
     *
     * @return string
     */
    protected function askDatabasePassword()
    {
        $databasePassword = $this->command->ask('Enter your database password.', env('DB_PASSWORD', ''));

        return $databasePassword;
    }

    /**
     * Is the database connection valid?
     *
     * @return bool
     */
    protected function databaseConnectionIsValid($host, $username, $password, $database)
    {
        try {
            $link = @mysqli_connect($host, $username, $password, $database);

            if (!$link) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

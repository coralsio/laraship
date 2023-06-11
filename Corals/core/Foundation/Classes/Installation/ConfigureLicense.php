<?php

namespace Corals\Foundation\Classes\Installation;

use Exception;
use GuzzleHttp\TransferStats;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ConfigureLicense
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function fire(Command $command)
    {
        $this->command = $command;

        $connected = false;

        while (!$connected) {
            $domain = $this->askInstallationDomain();
            $license = $this->askLicenseKey();

            $result = $this->LicenseIsValid($domain, $license);

            $status = false;

            if (is_array($result)) {
                $status = $result['status'] == 'success';
                $message = $result['message'] ?? '';
            }

            $connected = $status;

            if (!empty($message)) {
                $command->line($message, $status ? 'info' : 'error');
            }
        }

        $this->write($domain, $license);

        $command->info('License successfully configured.');
    }

    /**
     * @param $domain
     * @param $license
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function write($domain, $license)
    {
        try {
            $environmentFile = $this->finder->get($this->file);
        } catch (\Exception $exception) {
            $environmentFile = $this->finder->get($this->template);
        }

        $envArray = array_filter(preg_split('/\r\n|\r|\n/', $environmentFile));

        $search = [];

        foreach ($envArray as $value) {
            if (Str::startsWith($value, 'LICENSE_KEY') || Str::startsWith($value, 'APP_URL')) {
                $search[] = $value;
            }
        }

        $replace = [
            "APP_URL=$domain",
            "LICENSE_KEY=$license",
        ];

        $newEnvironmentFile = str_replace($search, $replace, $environmentFile);

        $this->finder->put($this->file, $newEnvironmentFile);
    }

    /**
     * @return string
     */
    protected function askInstallationDomain()
    {
        do {
            $domain = $this->command->ask('Enter your application domain (the domain that needs to be attached to your license)');

            $validator = Validator::make(['domain' => $domain], [
                'domain' => ['required', 'url', function ($attribute, $value, $fail) {
                    if ($this->strpos_arr($value)) {
                        $fail($attribute . ' not a valid live url.');
                    }
                },],
            ]);

            if ($validator->fails()) {
                $this->command->warn(join('|', $validator->errors()->get('domain')));
                $domain = '';
            }
        } while (empty($domain));

        return $domain;
    }

    /**
     * @return string
     */
    protected function askLicenseKey()
    {
        do {
            $license = $this->command->ask('Enter your License key');

            $validator = Validator::make(['license' => $license], [
                'license' => 'required',
            ]);

            if ($validator->fails()) {
                $this->command->warn(join('|', $validator->errors()->get('license')));
                $license = '';
            }
        } while (empty($license));

        return $license;
    }

    private function strpos_arr($domain)
    {
        $haystack = ['localhost', '.loc', '.dev', '127.0.0.1', 'dev.', 'test'];

        foreach ($haystack as $what) {
            if (($pos = strpos($domain, $what)) !== false) return true;
        }

        return false;
    }

    /**
     * @param $domain
     * @param $license
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function LicenseIsValid($domain, $license)
    {
        try {
            $client = new \GuzzleHttp\Client();

            $res = $client->request('GET', config('settings.models.module.updater_url'), [
                'query' => [
                    'license_key' => $license,
                    'domain' => $domain,
                    'action' => 'checkLicense',
                    'laravel_version' => app()->version(),
                ],
                'on_stats' => function (TransferStats $stats) use (&$url) {
                    $url = $stats->getEffectiveUri();
                }
            ]);

            $check_updates_result = json_decode($res->getBody(), true);

            return $check_updates_result;
        } catch (Exception $e) {
            $this->command->error($e->getMessage());
            return false;
        }
    }
}

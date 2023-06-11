<?php

if (!class_exists(\Laravel\Passport\Passport::class)) {
    throw new \Exception('Run composer update first');
}

\Madzipper::make(__DIR__ . '/3.0.zip')->extractTo(base_path());

\Madzipper::close();

\Artisan::call('migrate', ['--path' => 'vendor/laravel/passport/database/migrations']);

\Artisan::registerCommand(new \Laravel\Passport\Console\InstallCommand());
\Artisan::registerCommand(new \Laravel\Passport\Console\ClientCommand());
\Artisan::registerCommand(new \Laravel\Passport\Console\KeysCommand());

\Artisan::call('passport:install');
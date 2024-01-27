<?php

use Symfony\Component\Yaml\Yaml;

/*
|--------------------------------------------------------------------------
| Webservices Configuration
|--------------------------------------------------------------------------
|
| Do not change values in this file!
| Use the `laravice.config.yaml` instead!
| If the config file is missing, run `php artisan laravice:config`.
|
*/

$configFile = base_path('laravice.config.yaml');
$config = file_exists($configFile) ? Yaml::parseFile($configFile) : [];

return [
    'packages' => [
        'spatie/laravel-pdf' => data_get($config, 'packages.spatie/laravel-pdf', true),
    ],
];

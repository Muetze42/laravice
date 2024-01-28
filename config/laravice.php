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
    'download-as-default' => data_get($config, 'download-as-default', true),
    'packages' => [
        '@imgly/background-removal-node' => data_get($config, 'packages.@imgly/background-removal-node', true),
    ],
];

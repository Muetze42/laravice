<?php

return [

    'commands' => [
        /*
        |--------------------------------------------------------------------------
        | Node And NPM Command
        |--------------------------------------------------------------------------
        |
        | These values are used when executing processes.
        | `npm` for running NPM scripts
        | `node` for running Node.js scripts
        |
        */

        'npm' => env('PROCESS_NPM_CLI_COMMAND', 'npm'),
        'node' => env('PROCESS_NODE_CLI_COMMAND', 'node'),
    ],
];

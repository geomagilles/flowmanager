<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Job Driver
    |--------------------------------------------------------------------------
    |
    | Supported: "sync", "swf", "queue"
    |
    */

    'default' => 'queue',

    /*
    |--------------------------------------------------------------------------
    | Job Connections
    |--------------------------------------------------------------------------
    |
    */

    'connections' => [

        'sync' => [
            'driver' => 'sync',
        ],
        
        'queue' => [
            'driver' => 'queue',
        ],

        'swf' => [
            'driver'  => 'swf',
            'decider' => [
                'doFlow' => [
                    'class'   => 'Geomagilles\FlowManager\Tasks\Swf\Decider\Decider.php',
                ],
            ],
            'worker'  => [
                'doJob' => [
                    'class'  => 'Geomagilles\FlowManager\Tasks\Swf\Worker\Worker.php',
                ],
            ],
        ],
    ],
];

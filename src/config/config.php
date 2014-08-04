<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Job Driver
    |--------------------------------------------------------------------------
    |
    | Supported: "queue", "sync" (soon "swf")
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
            'worker' => [
                'queue'  => 'flowmanager_worker'
            ],
            'decider' => [
                'queue'  => 'flowmanager_decider'
            ]
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

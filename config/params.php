<?php

return [
    'adminEmail' => 'admin@example.com',

    // beanstalk properties
    'beanstalkd' => [
        'host' => '127.0.0.1',
        'port' => '11300',

        // custom options for worker
        'errorJobsLimit' => 5
    ]
];

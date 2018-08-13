<?php

return [
    //sqlite
    'cron' => [
        'db' => [
            'driver' => 'pdo_sqlite',
            'path' => FIXTURES_PATH.'/database/cron.sqlite'
        ]
    ]
];
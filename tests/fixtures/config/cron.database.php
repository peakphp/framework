<?php

return [
    //sqlite
    'crondb' => [
        'driver' => 'pdo_sqlite',
        'path' => FIXTURES_PATH.'/database/cron.sqlite'
    ]
];
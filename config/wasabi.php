<?php

return [
    "app" => [
        'pickles2' => [
            'migration' => 'App\\Wasabi\\pickles2::migrate',
            'cli' => 'App\\Wasabi\\pickles2::cli',
            'web' => 'App\\Wasabi\\pickles2::register',
        ],
    ],
];

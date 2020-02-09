<?php

return [
    "app" => [
        'pickles2' => [
            'migrate' => 'App\\Wasabi\\Pickles2\\pickles2::migrate',
            'cli' => 'App\\Wasabi\\Pickles2\\pickles2::cli',
            'web' => 'App\\Wasabi\\Pickles2\\pickles2::web',
        ],
    ],
];

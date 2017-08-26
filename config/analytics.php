<?php

return [
    'codes' => [

        'google' => env('APP_ANALYTICS_GOOGLE'),

        'facebook' => env('APP_ANALYTICS_FACEBOOK')
    ],

    'cookies' => [
        'cnil' => [
            'days' => '366',
            'name' => 'traking',
            'value' => true
        ]
    ]
];
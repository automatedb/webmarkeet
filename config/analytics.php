<?php

return [
    'codes' => [
        'google' => env('APP_ANALYTICS_GOOGLE'),

        'facebook' => env('APP_ANALYTICS_FACEBOOK')
    ],

    'cookies' => [
        'cnil' => [
            'days' => env('APP_COOKIES_CNIL_DAYS', '366'),
            'name' => env('APP_COOKIES_CNIL_NAME', 'traking'),
            'value' => env('APP_COOKIES_CNIL_VALUE', true)
        ],
        'facebook_refuse_registration' => [
            'days' => env('APP_FACEBOOK_REFUSE_REGISTRATION.DAYS', '1'),
            'name' => env('APP_FACEBOOK_REFUSE_REGISTRATION.NAME', 'facebook_registered'),
            'value' => env('APP_FACEBOOK_REFUSE_REGISTRATION.VALUE', true)
        ]
    ]
];
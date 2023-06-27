<?php
return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],
    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],
    'providers' => [
        'users' => [
            'driver' => 'fusionauth_eloquent',
            'model' => App\Models\User::class,
        ],
    ],
    'passwords' => [],
    'password_timeout' => 10800,
];

<?php

return [
    'provider' => 'brasil-api',

    'providers' => [
        'brasil-api' => \App\Libs\CityApi\Providers\BrasilApi\BrasilApiCity::class,
    ],

    'configs' => [
        'brasil-api' => [
            'base_url' => 'https://brasilapi.com.br/api/ibge/municipios/v1/',
            'setup' => \App\Libs\CityApi\Providers\BrasilApi\Setup\BrasilApiSetup::class,
        ],
    ]
];

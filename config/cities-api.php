<?php

return [
    'provider' => env('CITIES_API_PROVIDER', 'brasil-api'),

    'providers' => [
        'brasil-api' => \App\Libs\CityApi\Providers\BrasilApi\BrasilApiCity::class,
        'ibge-gov-br' => \App\Libs\CityApi\Providers\IbgeGovBr\IbgeGovBrCity::class,
    ],

    'configs' => [
        'brasil-api' => [
            'base_url' => 'https://brasilapi.com.br/api/ibge/municipios/v1/',
            'setup' => \App\Libs\CityApi\Providers\BrasilApi\Setup\BrasilApiSetup::class,
        ],
        'ibge-gov-br' => [
            'base_url' => 'https://servicodados.ibge.gov.br/api/v1/',
            'setup' => \App\Libs\CityApi\Providers\IbgeGovBr\Setup\IbgeGovBrSetup::class,
        ],
    ]
];

<?php

namespace App\Libs\CityApi\Providers\BrasilApi;

use App\Helpers\CityFormat;
use App\Libs\Common\HttpClient;
use App\Libs\CityApi\Validators\ValidCity;
use App\Libs\CityApi\Managers\CityListManager;
use App\Libs\CityApi\Helpers\CacheHttpResponse;
use App\Libs\CityApi\Contracts\CityListContract;
use App\Libs\CityApi\Contracts\CityProviderContract;
use App\Libs\CityApi\Providers\BrasilApi\Setup\BrasilApiSetup;
use App\Traits\DebugMethods;
use Illuminate\Support\Collection;

class BrasilApiCity implements CityProviderContract
{
    use DebugMethods;

    protected static ?CacheHttpResponse $cacheHttpResponse = \null;
    protected static array $bindKeys = [
        'name' => 'nome',
        'ibge_id' => 'codigo_ibge',
    ];

    public static function getCityListByUf(string $uf): CityListContract
    {
        if (!trim($uf)) {
            return \null;
        }

        $uf = \strtoupper($uf);

        $responseData = static::cacheHttp()->cache('get', __METHOD__, [$uf]);

        return new CityListManager(($responseData ?: []), static::$bindKeys, 'brasil-api');
    }

    public static function cacheHttp(): CacheHttpResponse
    {
        return static::$cacheHttpResponse = static::$cacheHttpResponse ?? new CacheHttpResponse(static::http());
    }

    public static function searchCity(string $cityInfo, string $uf): Collection
    {
        $uf = \strtoupper($uf);

        $cityInfoIsNumber = \is_numeric($cityInfo);

        if ($cityInfoIsNumber) {
            return static::getCityListByUf($uf)
                ->filter(
                    fn ($item) => ($item['ibge_id'] ?? \null) == $cityInfo,
                );
        }

        return static::getCityListByUf($uf)
            ->filter(
                fn ($item) => \str_contains(
                    CityFormat::standadizeTheNames($item['name'] ?? ''),
                    CityFormat::standadizeTheNames($cityInfo)
                ),
            );
    }

    public static function first(string $cityName, string $uf): null|ValidCity
    {
        try {
            return ValidCity::make(static::searchCity($cityName, $uf)->first());
        } catch (\Throwable $th) {
            if (static::debug()) {
                throw $th;
            }

            return \null;
        }
    }

    public static function http(?array $config = null): HttpClient
    {
        return HttpClient::instance(
            BrasilApiSetup::instance($config)
        );
    }

    public static function getSetupClass(): ?string
    {
        return BrasilApiSetup::class;
    }
}

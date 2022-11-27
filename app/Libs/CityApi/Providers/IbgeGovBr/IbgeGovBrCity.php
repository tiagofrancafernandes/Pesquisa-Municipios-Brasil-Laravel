<?php

namespace App\Libs\CityApi\Providers\IbgeGovBr;

use App\Helpers\CityFormat;
use App\Libs\Common\HttpClient;
use App\Libs\CityApi\Validators\ValidCity;
use App\Libs\CityApi\Managers\CityListManager;
use App\Libs\CityApi\Helpers\CacheHttpResponse;
use App\Libs\CityApi\Contracts\CityListContract;
use App\Libs\CityApi\Contracts\CityProviderContract;
use App\Libs\CityApi\Providers\IbgeGovBr\Setup\IbgeGovBrSetup;
use App\Traits\DebugMethods;
use Illuminate\Support\Collection;

class IbgeGovBrCity implements CityProviderContract
{
    use DebugMethods;

    protected static ?CacheHttpResponse $cacheHttpResponse = \null;
    protected static array $bindKeys = [
        'name' => 'nome',
        'ibge_id' => 'id',
    ];

    public static function getCityListByUf(string $uf): CityListContract
    {
        if (!trim($uf)) {
            return \null;
        }

        $uf = \strtoupper($uf);

        $responseData = static::cacheHttp()->cache('get', __METHOD__, ["localidades/estados/{$uf}/municipios"]);

        return new CityListManager(($responseData ?: []), static::$bindKeys);
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
            IbgeGovBrSetup::instance($config)
        );
    }

    public static function getSetupClass(): ?string
    {
        return IbgeGovBrSetup::class;
    }
}

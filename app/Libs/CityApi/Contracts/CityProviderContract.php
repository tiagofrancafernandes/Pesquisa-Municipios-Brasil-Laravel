<?php

namespace App\Libs\CityApi\Contracts;

use App\Libs\CityApi\Validators\ValidCity;
use Illuminate\Support\Collection;

interface CityProviderContract
{
    public static function getCityListByUf(string $uf): CityListContract;
    public static function searchCity(string $cityName, string $uf): Collection;
    public static function first(string $cityName, string $uf): null|ValidCity;
    public static function getSetupClass(): ?string;
}

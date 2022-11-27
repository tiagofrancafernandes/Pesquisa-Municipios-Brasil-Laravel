<?php

namespace App\Libs\CityApi\Contracts;

use Illuminate\Support\Collection;

interface ValidateCityContract
{
    public function __construct(array $cityInfo);
    public function data(bool $toArray = false): null|array|Collection;
    public static function make(array $cityInfo): null|ValidateCityContract;
}

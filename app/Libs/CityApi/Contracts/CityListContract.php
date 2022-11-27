<?php

namespace App\Libs\CityApi\Contracts;

use App\Libs\CityApi\Validators\ValidCity;
use Illuminate\Support\Collection;

interface CityListContract
{
    public function __construct(array $cityList, array $bindKeys = []);

    public function cities(): Collection;

    public function filter(callable $callableFilter): Collection;

    public function first(?callable $callback = \null): null|ValidCity;
}

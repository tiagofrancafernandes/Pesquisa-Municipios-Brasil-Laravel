<?php

namespace App\Libs\CityApi\Managers;

use App\Helpers\CityFormat;
use App\Libs\CityApi\Contracts\CityListContract;
use App\Libs\CityApi\Validators\ValidCity;
use Illuminate\Support\Collection;

class CityListManager implements CityListContract
{
    protected ?Collection $cityList = \null;

    public function __construct(array $cityList, array $bindKeys = [])
    {
        if (!$cityList) {
            return;
        }

        $this->cityList = \collect();

        $keyName = $bindKeys['name'] ?? 'name';
        $keyIbgeId = $bindKeys['ibge_id'] ?? 'ibge_id';

        foreach ($cityList as $city) {
            $name = $city[$keyName] ?? $city[0][$keyName] ?? \null;
            $ibgeId = $city[$keyIbgeId] ?? $city[0][$keyIbgeId] ?? \null;

            if (!$name || !$ibgeId) {
                continue;
            }

            $this->cityList->push([
                'name' => CityFormat::standadizeTheNames($name),
                'ibge_id' => (int) $ibgeId,
                'provider' => config('cities-api.provider'),
            ]);
        }

        if (!$this->cityList->count()) {
            return;
        }
    }

    public function cities(): Collection
    {
        return collect($this->cityList);
    }

    public function filter(callable $callableFilter): Collection
    {
        $cities = $this->cities();
        return $cities->filter($callableFilter);
    }

    public function first(?callable $callback = \null): null|ValidCity
    {
        if (!$this->cities()->count()) {
            return null;
        }

        $firstCity = $this->cities()->first($callback);

        return $firstCity ? new ValidCity($firstCity) : \null;
    }
}

<?php

namespace App\Libs\CityApi\Managers;

use App\Libs\CityApi\Contracts\CityProviderContract;
use Exception;

class CityRequestManager
{
    protected CityProviderContract $provider;

    public function __construct(CityProviderContract $provider)
    {
        $this->provider = $provider;
    }

    public function getProvider(): ?CityProviderContract
    {
        return $this->provider;
    }
}

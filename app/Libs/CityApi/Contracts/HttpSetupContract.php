<?php

namespace App\Libs\CityApi\Contracts;

interface HttpSetupContract
{
    public function __construct(array $config = []);
    public function baseUrl(): string;
    public function setBaseUrl(string $baseUrl): void;
    public function getBaseUrl(): string;
}

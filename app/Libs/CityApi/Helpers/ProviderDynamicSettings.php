<?php

namespace App\Libs\CityApi\Helpers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use App\Libs\CityApi\Contracts\HttpSetupContract;
use App\Libs\CityApi\Contracts\CityProviderContract;
use Str;

class ProviderDynamicSettings
{
    private string $initTime;

    public function __construct()
    {
        $this->initTime = date('Y-m-d H:i:s');
    }

    public static function getProviderClass(string $providerName = \null): ?string
    {
        $providers = config('cities-api.providers');
        $providerName ??= config('cities-api.provider');

        if (!\in_array($providerName, \array_keys($providers), \true)) {
            throw new Exception("Error: Invalid provider '{$providerName}'.", 1005);
        }

        return $providers[$providerName] ?? \null;
    }

    public static function getProviderSetupClass(): ?string
    {
        $providerClass = static::getProviderClass();
        return $providerClass::getSetupClass();
    }

    public function initTime()
    {
        return $this->initTime;
    }

    public static function loadSingletons(Application $app): void
    {
        $providerClass = ProviderDynamicSettings::getProviderClass();

        $toBind = [
            CityProviderContract::class => [
                'class' => $providerClass,
                'aliasToClassName' => true,
                'params' => [],
            ],
            ProviderDynamicSettings::class => [
                'class' => ProviderDynamicSettings::class,
                'aliasToClassName' => true,
                'params' => [],
            ],
        ];

        static::bindEach($app, $toBind);
    }

    public static function loadBinds(Application $app): void
    {
        $providerClass = ProviderDynamicSettings::getProviderClass();
        $providerSetupClass = ProviderDynamicSettings::getProviderSetupClass();

        $toBind = [
            CityProviderContract::class => [
                'class' => $providerClass,
                'aliasToClassName' => true,
                'params' => [],
            ],
            HttpSetupContract::class => [
                'class' => $providerSetupClass,
                'aliasToClassName' => true,
                'params' => [],
            ],
        ];

        static::bindEach($app, $toBind);
    }

    protected static function bindEach(Application $app, array $toBind)
    {
        foreach ($toBind as $key => $value) {
            $class = $value['class'] ?? \null;
            $aliasToClassName = $value['aliasToClassName'] ?? \null;
            $params = $value['params'] ?? [];

            if (!$key || !$value || !$class) {
                continue;
            }

            $instanceToBind = $params ? new $class(...$params) : new $class();
            $app->bind($key, fn (Application $app) => $instanceToBind);

            if (!$aliasToClassName) {
                continue;
            }

            $classNameOnly = Str::afterLast($key, '\\');
            $app->bind($classNameOnly, fn (Application $app) => $instanceToBind);
        }
    }
}

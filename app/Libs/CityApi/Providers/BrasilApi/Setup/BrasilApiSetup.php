<?php

namespace App\Libs\CityApi\Providers\BrasilApi\Setup;

use App\Libs\CityApi\Contracts\HttpSetupContract;
use Exception;

class BrasilApiSetup implements HttpSetupContract
{
    private ?string $baseUrl;

    private static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    private static array $requiredItems = [
        'base_url',
    ];

    public function __construct(?array $config = null)
    {
        $config = $config ?: static::fromConfigSet();

        static::validateStartConfig($config);

        $this->baseUrl = $config['baseUrl'] ?? null;
    }

    /**
     * function instance
     *
     * @param ?array $config
     *
     * @return HttpSetupContract
     */
    public static function instance(?array $config = null): HttpSetupContract
    {
        return new static($config ?? static::fromConfigSet());
    }

    /**
     * function fromConfigSet
     *
     * @return array
     */
    public static function fromConfigSet()
    {
        return [
            'base_url' => config('cities-api.configs.brasil-api.base_url'),
        ];
    }

    /**
     * function validateStartConfig
     *
     * @param array $configData
     * @return
     */
    protected static function validateStartConfig(array $configData): array
    {
        if (!$configData || \array_is_list($configData)) {
            throw new Exception(
                "Error: Invalid 'configData' " . \PHP_EOL . __METHOD__,
                100
            );
        }

        $configCollect = \collect($configData);

        if (!$configCollect->has(static::$requiredItems)) {
            throw new Exception(
                "Error: Invalid 'configData'. Configs are missing. " . \PHP_EOL . __METHOD__,
                110
            );
        }

        $emptyItems = $configCollect->filter(
            fn ($value, $key) => empty($value) && \in_array($key, static::$requiredItems)
        );

        if ($emptyItems->count()) {
            throw new Exception(
                "Error: Invalid 'configData'. Empty configs: {$emptyItems->keys()->implode(', ')}. " . \PHP_EOL . __METHOD__,
                120
            );
        }

        return $configData ?? [];
    }

    /**
     * function baseUrl
     *
     * @TODO retornar uma validação de URL antes de retornar string
     *
     * @return string
     */
    public function baseUrl(): string
    {
        return $this->getBaseUrl();
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * function getBaseUrl
     *
     * @TODO retornar uma validação de URL antes de retornar string
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl ?? static::fromConfigSet()['base_url'] ?? \null;
    }

    /**
     * function getHeaders
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return static::$headers ?? [];
    }
}

<?php

namespace App\Libs\CityApi\Helpers;

use Illuminate\Support\Facades\Cache;
use App\Libs\Common\HttpClient;

class CacheHttpResponse
{
    protected HttpClient $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * cache function
     *
     * @param string $method
     * @param string $cacheKeySalt
     * @param array $params
     * @param integer $ttl
     * @return array
     */
    public function cache(string $method, string $cacheKeySalt, array $params, ?int $ttl = null): array
    {
        $ttl ??= (60 * 60 * 24);

        return Cache::remember(
            \implode('-', [
                $cacheKeySalt,
                ...$params
            ]),
            $ttl /*secs*/,
            function () use ($params, $method): array {
                $response = $this->httpClient->{$method}(...$params);

                if (!$response->ok()) {
                    return [];
                }

                return $response->json();
            }
        );
    }
}

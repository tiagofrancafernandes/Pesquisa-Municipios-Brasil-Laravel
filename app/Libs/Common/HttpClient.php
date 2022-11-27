<?php

namespace App\Libs\Common;

use App\Libs\CityApi\Contracts\HttpSetupContract;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

class HttpClient
{
    protected ?HttpSetupContract $config = null;
    protected static null|Http|PendingRequest $httpClient = null;

    public function __construct(?HttpSetupContract $setup = null)
    {
        $this->setup($setup);
    }

    /**
     * function setup
     *
     * @param ?HttpSetupContract $setup = null
     * @return void
     */
    protected function setup(?HttpSetupContract $setup = null): void
    {
        $this->config = static::config($setup);
        static::$httpClient = static::httpClient($setup);
    }

    /**
     * function instance
     *
     * @param ?HttpSetupContract $setup
     * @return HttpClient
     */
    public static function instance(?HttpSetupContract $setup = null): HttpClient
    {
        return new static($setup);
    }

    /**
     * function config
     *
     * @param ?HttpSetupContract $setup = null
     * @return HttpSetupContract
     */
    public static function config(?HttpSetupContract $setup = null): HttpSetupContract
    {
        return $setup ?? HttpSetupContract::instance();
    }

    /**
     * function httpClient
     *
     * @param ?HttpSetupContract $setup
     * @return \Illuminate\Support\Facades\Http
     */
    public static function httpClient(?HttpSetupContract $setup = null, bool $newInstance = false): Http|PendingRequest
    {
        /** @var Http|PendingRequest $instanciateHttp */
        $instanciateHttp = function (?HttpSetupContract $setup) {
            return Http::baseUrl(
                static::config($setup)->baseUrl()
            )->withHeaders(
                static::headers($setup)
            );
        };

        if ($newInstance) {
            return $instanciateHttp($setup);
        }

        return static::$httpClient ?? $instanciateHttp($setup);
    }

    /**
     * get function
     *
     * @param string $url
     * @param array $params
     * @return Response
     */
    public function get(string $url, array $params = []): Response
    {
        return static::httpClient()->get($url, $params);
    }

    /**
     * post function
     *
     * @param string $url
     * @param array $data
     * @return Response
     */
    public function post(string $url, array $data = []): Response
    {
        return static::httpClient()->post($url, $data);
    }

    /**
     * put function
     *
     * @param string $url
     * @param array $data
     * @return Response
     */
    public function put(string $url, array $data = []): Response
    {
        return static::httpClient()->put($url, $data);
    }

    /**
     * delete function
     *
     * @param string $url
     * @return Response
     */
    public function delete(string $url): Response
    {
        return static::httpClient()->delete($url);
    }

    public static function headers(?HttpSetupContract $setup = null, array $appendHeaders = [])
    {
        $setup ??= static::config($setup);

        return array_merge($setup->getHeaders(), $appendHeaders);
    }
}

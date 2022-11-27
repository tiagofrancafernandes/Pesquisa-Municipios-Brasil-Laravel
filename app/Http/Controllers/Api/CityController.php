<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Libs\CityApi\Contracts\CityProviderContract;
use App\Libs\CityApi\Helpers\ProviderDynamicSettings;
use App\Libs\CityApi\Managers\CityRequestManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    protected CityRequestManager $cityRequestManager;
    protected CityProviderContract $providerInstance;

    public function __construct()
    {
        $this->cityRequestManager = app(CityRequestManager::class);
        $this->providerInstance = app(CityRequestManager::class)->getProvider();
    }

    /**
     * function search
     *
     * @param Request $request
     * @param $uf
     * @return
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uf' => 'required|string|min:2|max:2',
            'city' => 'required_without:ibge_id|string|min:2',
            'ibge_id' => 'nullable|numeric|min:2',
        ]);

        if ($validator->errors()->count()) {
            return response()->json($validator->errors(), 400);
        }

        $uf = $request->input('uf');
        $city = (string) ($request->input('city') ?? $request->input('ibge_id'));

        $data = $this->providerInstance->searchCity($city, $uf)->all();

        return response()->json($data, 200);
    }

    /**
     * function cityByIbgeId
     *
     * @param Request $request
     * @param $uf
     * @return
     */
    public function cityByIbgeId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uf' => 'required|string|min:2|max:2',
            'ibge_id' => 'required|numeric|min:2',
        ]);

        if ($validator->errors()->count()) {
            return response()->json($validator->errors(), 400);
        }

        $uf = $request->input('uf');
        $city = $request->input('ibge_id');

        $data = $this->providerInstance->searchCity($city, $uf)->all();

        return response()->json($data, 200);
    }

    /**
     * function citiesByUfAndProvider
     *
     * @param Request $request
     * @param $uf
     * @return
     */
    public function citiesByUfAndProvider(Request $request, string $uf = \null, string $provider = \null)
    {
        $request['uf'] = $uf ?? $request->input('uf');
        $request['provider'] = $provider ?? $request->input('provider');

        $validator = Validator::make($request->all(), [
            'uf' => 'required|string|min:2|max:2',
            'provider' => 'nullable|string|min:2',
        ]);

        $provider = $request->input('provider');

        if ($validator->errors()->count()) {
            return response()->json($validator->errors(), 400);
        }

        $providers = (array) config('cities-api.providers', []);

        if (
            $provider && !\in_array($provider, \array_keys($providers))
        ) {
            return response()->json([
                'error' => 'Invalid provider',
                'available_providers' => \array_keys($providers),
            ], 404);
        }

        $providerClass = ProviderDynamicSettings::getProviderClass($provider);

        $providerInstance = $provider ? new $providerClass() : $this->providerInstance;

        return response()->json(
            $providerInstance->getCityListByUf(
                $request->input('uf')
            )->cities(),
            200
        );
    }
}

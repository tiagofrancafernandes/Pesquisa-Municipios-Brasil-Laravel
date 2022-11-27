<?php

namespace Tests\Feature;

use App\Helpers\CityFormat;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CitySearchTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function checkIfCityCameOnTheList()
    {
        $cityName = 'montanhas';
        $expectedCityName = CityFormat::standadizeTheNames($cityName); //Montanhas
        $ibgeId = 2407708;

        $response = $this->json('GET', '/api/cities/search', [
            'city' => $cityName,
            'uf' => 'rn',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'name',
                'ibge_id',
            ]
        ]);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has(1)
                ->first(
                    fn ($json) =>
                    $json->where('ibge_id', $ibgeId)
                        ->where('name', $expectedCityName)
                        ->etc()
                )
        );

        $response->assertJson(
            fn (AssertableJson $json) => $json->has(1)
                ->each(fn ($item) => $item->hasAll(['name', 'ibge_id']))
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function checkIfCityCameOnTheListUsingPartialName()
    {
        $cityName = 'montanh';
        $expectedCityName = CityFormat::standadizeTheNames('Montanhas'); //Montanhas
        $ibgeId = 2407708;

        $response = $this->json('GET', '/api/cities/search', [
            'city' => $cityName,
            'uf' => 'rn',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'name',
                'ibge_id',
            ]
        ]);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has(1)
                ->first(
                    fn ($json) =>
                    $json->where('ibge_id', $ibgeId)
                        ->where('name', $expectedCityName)
                        ->etc()
                )
        );

        $response->assertJson(
            fn (AssertableJson $json) => $json->has(1)
                ->each(fn ($item) => $item->hasAll(['name', 'ibge_id']))
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function checkIfCityCameOnTheListUsingIbgeIdInsteadOfName()
    {
        $expectedCityName = CityFormat::standadizeTheNames('Montanhas'); //Montanhas
        $ibgeId = 2407708;

        $response = $this->json('GET', '/api/cities/search', [
            'ibge_id' => $ibgeId,
            'uf' => 'rn',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'name',
                'ibge_id',
            ]
        ]);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has(1)
                ->first(
                    fn ($json) =>
                    $json->where('ibge_id', $ibgeId)
                        ->where('name', $expectedCityName)
                        ->etc()
                )
        );

        $response->assertJson(
            fn (AssertableJson $json) => $json->has(1)
                ->each(fn ($item) => $item->hasAll(['name', 'ibge_id']))
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function checkIfCityCameOnTheListUsingIbgeId()
    {
        $expectedCityName = CityFormat::standadizeTheNames('Montanhas'); //Montanhas
        $ibgeId = 2407708;

        $response = $this->json('GET', '/api/cities/by-ibge-id', [
            'ibge_id' => $ibgeId,
            'uf' => 'rn',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'name',
                'ibge_id',
            ]
        ]);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has(1)
                ->first(
                    fn ($json) =>
                    $json->where('ibge_id', $ibgeId)
                        ->where('name', $expectedCityName)
                        ->etc()
                )
        );

        $response->assertJson(
            fn (AssertableJson $json) => $json->has(1)
                ->each(fn ($item) => $item->hasAll(['name', 'ibge_id']))
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function checkIfGetErrorIfMissingParameters()
    {
        $response = $this->json('GET', '/api/cities/search', [
            'uf' => 'rn',
        ]);

        $response->assertStatus(400);

        $response->assertJson(
            fn (AssertableJson $json) => $json->has(1)
                ->has('city')
        );

        $this->assertTrue(
            ($response->json()['city'][0] ?? null) == 'The city field is required when ibge id is not present.'
        );
    }
}

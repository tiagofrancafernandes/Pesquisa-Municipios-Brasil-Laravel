<?php

use App\Http\Controllers\Api\CityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', fn (Request $request) => $request->user());

Route::prefix('city')->name('city.')->group(function () {
    Route::get('search', [CityController::class, 'search'])->name('search');
    Route::get('by-ibge-id', [CityController::class, 'cityByIbgeId'])->name('cityByIbgeId');
    Route::get('list/{uf}/{provider?}', [CityController::class, 'citiesByUfAndProvider'])->name('list');
    Route::get('show/{uf?}/{cityName?}/{provider?}', [CityController::class, 'cityByUfAndName'])->name('show');
});

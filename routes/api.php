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

Route::prefix('cities')->name('cities.')->group(function () {
    Route::get('search', [CityController::class, 'search'])->name('search');
    Route::get('by-ibge-id', [CityController::class, 'cityByIbgeId'])->name('cityByIbgeId');
});

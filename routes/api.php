<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Kavist\RajaOngkir\Facades\RajaOngkir;
use Illuminate\Support\Facades\Log;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/provinces', 'RajaOngkirController@getProvinces');
Route::get('/cities/{province}', 'RajaOngkirController@getCities');
Route::post('/shipping-cost', function (Request $request) {
    try {
        $cost = RajaOngkir::ongkosKirim([
            'origin'        => 155,  // ID kota/kabupaten asal, e.g., Jakarta Pusat
            'destination'   => $request->destination,
            'weight'        => $request->weight,
            'courier'       => $request->courier
        ])->get();

        return response()->json($cost[0]['costs']);
    } catch (\Exception $e) {
        Log::error('RajaOngkir API Error: ' . $e->getMessage());
        return response()->json(['error' => 'Terjadi kesalahan saat menghitung biaya pengiriman.'], 500);
    }
});

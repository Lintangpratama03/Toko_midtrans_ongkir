<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class RajaOngkirController extends Controller
{
    public function getProvinces()
    {
        $provinces = RajaOngkir::provinsi()->all();
        return response()->json($provinces);
    }

    public function getCities($provinceId)
    {
        $cities = RajaOngkir::kota()->dariProvinsi($provinceId)->get();
        return response()->json($cities);
    }

    public function getShippingCost(Request $request)
    {
        $cost = RajaOngkir::ongkosKirim([
            'origin'        => 155,  // ID kota/kabupaten asal
            'destination'   => $request->destination,
            'weight'        => $request->weight,
            'courier'       => $request->courier
        ])->get();

        return response()->json($cost[0]['costs']);
    }
}

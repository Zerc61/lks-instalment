<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstalmentCar;
use App\Models\InstalmentMonth;

class InstalmentCarController extends Controller
{
    public function index() {
        $cars = InstalmentCar::all();

        return response()->json([
            'cars' => $cars
        ]);
    }

    public function months($id) {
        $months = InstalmentMonth::where('instalment_car_id', $id)->get();

        return response()->json([
            'months' => $months
        ]);
    }
}

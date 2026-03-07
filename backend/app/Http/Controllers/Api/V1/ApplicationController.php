<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstalmentCar;
use App\Models\InstalmentMonth;
use App\Models\Application;

class ApplicationController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'instalment_car_id' => 'required|exists:instalment_cars,id',
            'months' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        $validMonth = InstalmentMonth::where('instalment_car_id', $request->instalment_car_id)->where('month', $request->months)->exists();

        if(!$validMonth) {
            return response()->json([
                'message' => 'invalid instalment month for this car'
            ], 400);
        }

        $hasPending = Application::where('society_id', $request->user()->id)
        ->where('apply_status', 'pending')->exists();

        if($hasPending) {
            return response()->json([
                'message' => 'You still have pending application'
            ], 400);
        }

        $car = InstalmentCar::find($request->instalment_car_id);

        $nominal = round($car->price / $request->months, 2);

        $application = Application::create([
            'society_id' => $request->user()->id,
            'instalment_car_id' => $request->instalment_car_id,
            'months' => $request->months,
            'nominal' => $nominal,
            'notes' => $request->notes,
            'apply_status' => 'pending'
        ]);

        return response()->json([
            'message' => 'application submitted',
            'application' => $application,
        ], 201);
    }

    public function index(Request $request) {
        $application = Application::where('society_id', $request->user()->id)->with('instalmentCar')->get();

        return response()->json([
            'applications' => $application
        ]);
    }

    public function adminIndex(Request $request)
{
    $query = Application::with(['society', 'instalmentCar'])
        ->orderBy('created_at', 'desc');

    if ($request->status) {
        $request->validate([
            'status' => 'in:pending,accepted,rejected'
        ]);

        $query->where('apply_status', $request->status);
    }

    $applications = $query->get();

    return response()->json([
        'applications' => $applications
    ]);
}

    public function updateStatus(Request $request, $id) {
        $request->validate([
            'apply_status' => 'required|in:accepted,rejected',
        ]);

        $application = Application::findOrFail($id);
        

        $application->update($request->all());

        return response()->json([
            'message' => 'Application updated',
            'application' => $application
        ]);

    }
    
}

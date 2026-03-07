<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Validation;

class ValidationController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'job' => 'required|string',
            'job_description' => 'required|string',
            'income' => 'required|numeric|min:0',
            'reason_accepted' => 'required|string'
        ]);

        $exists = Validation::where('society_id', $request->user()->id)->exists();

        if($exists) {
            return response()->json([
                'message' => 'Validation already submitted'
            ], 400);
        }

        $validation = Validation::create([
            'society_id' => $request->user()->id,
            'job' => $request->job,
            'job_description' => $request->job_description,
            'income' => $request->income,
            'reason_accepted' => $request->reason_accepted,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Validation submitted',
            'validation' => $validation
        ], 201);
    }

    public function adminIndex(Request $request) {
        $query = Validation::with('society')->orderBy('created_at', 'desc');

        if($request->status) {
            $request->validate([
                'status' => 'in:pending,accepted,rejected'
            ]);

            $query->where('status', $request->status);
        }

        $validation = $query->get();

        return response()->json([
            'validations' => $validation
        ]);
    }

    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:accepted,rejected',
        'validator_notes' => 'nullable|string',
    ]);

    $validation = Validation::findOrFail($id);

    if ($validation->status !== 'pending') {
        return response()->json([
            'message' => 'validation already processed'
        ], 400);
    }

    $validation->update($request->all());

    return response()->json([
        'message' => 'validation updated',
        'validation' => $validation,
    ]);
}
}

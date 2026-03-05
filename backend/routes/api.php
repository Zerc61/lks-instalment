<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\InstalmentCarController;
use App\Http\Controllers\Api\V1\ApplicationController;
use App\Http\Controllers\Api\V1\ValidationController;

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::get('/instalment-cars', [InstalmentCarController::class, 'index']);
        Route::get('/instalment-cars/{id}/months', [InstalmentCarController::class, 'months']);

        Route::post('/applications', [ApplicationController::class, 'store']);
        Route::get('/applications', [ApplicationController::class, 'index']);

        Route::post('/validation', [ValidationController::class, 'store']);
        

            Route::middleware('auth:sanctum', 'admin')->prefix('admin')->group(function() {
                Route::get('/applications', [ApplicationController::class, 'adminIndex']);
                Route::patch('/applications/{id}', [ApplicationController::class, 'updateStatus']);
            });

            Route::get('/me', function(Request $request) {
               return response()->json([
                $request->user()
              ]);
           });
        });
    });


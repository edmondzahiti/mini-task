<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceProviderApiController;
use App\Http\Controllers\Api\CategoryApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes using Service Layer Architecture
Route::prefix('v1')->group(function () {
    
    // Categories API
    Route::get('/categories', [CategoryApiController::class, 'index']);
    
    // Service Providers API
    Route::get('/providers', [ServiceProviderApiController::class, 'index']);
    Route::get('/providers/{slug}', [ServiceProviderApiController::class, 'show']);
});

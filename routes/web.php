<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Vue.js SPA - Main Application
Route::get('/', function () {
    return view('vue-app');
})->name('home');

// Optimized providers route with caching
Route::get('/providers', [App\Http\Controllers\ProviderController::class, 'index'])->name('providers');



// Catch-all route for Vue.js SPA (handles all client-side routing)
Route::get('/{any}', function () {
    return view('vue-app');
})->where('any', '.*');

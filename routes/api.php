<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

// Route::GET('/valida', [AuthenticatedSessionController::class, 'validacodigo', function(Request $request){}]);

Route::post('logeocodigo', [AuthenticatedSessionController::class, 'ValidaCodigo', ])->name('logeocodigo');
Route::post('logeocodigocel', [AuthenticatedSessionController::class, 'ValidaCodigoCel'])->name('logeocodigocel');

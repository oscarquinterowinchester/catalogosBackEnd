<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/login')->controller(\App\Http\Controllers\loginController::class)->group(function (){
    Route::get('/', 'login');
    // Route::post('/user/create', 'create_user');
    // Route::post('/user/send_confirmation_email', 're_send_confomation_email');
    // Route::post('/user/delete_no_verified', 'delete_no_verified_user');
    
});

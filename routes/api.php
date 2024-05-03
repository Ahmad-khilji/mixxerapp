<?php

use App\Http\Controllers\Admin\Api\AuthController;
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

Route::post('user/auth/verify' ,[ AuthController::class, 'verify']);
Route::post('user/auth/otp/verify' ,[ AuthController::class, 'otpVerify']);
Route::post('user/auth/register' ,[ AuthController::class, 'register']);
Route::post('user/auth/login' ,[ AuthController::class, 'login']);
Route::post('user/auth/recover/password' ,[ AuthController::class, 'recoverPassword']);
Route::post('user/auth/new/password' ,[ AuthController::class, 'newPassword']);
Route::post('user/auth/social' ,[ AuthController::class, 'social']);
Route::post('user/auth/changePassword' ,[ AuthController::class, 'changePassword']);
Route::post('user/auth/logout' ,[ AuthController::class, 'logout']);
Route::post('user/auth/deleteAccount' ,[ AuthController::class, 'deleteAccount']);

<?php

use App\Http\Controllers\api\AuthController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

Route::post('/sms/forward',[\App\Http\Controllers\BotController::class, 'forwardMessageToTelegram'])->middleware('auth:sanctum');
Route::post('/user/config',[\App\Http\Controllers\api\AuthController::class, 'updateUserConfig'])->middleware('auth:sanctum');

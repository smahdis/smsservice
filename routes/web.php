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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/play',[\App\Http\Controllers\BotController::class, 'play']);
Route::post('/callback/{bot_name?}',[\App\Http\Controllers\BotController::class, 'callback'])->middleware('telegram');


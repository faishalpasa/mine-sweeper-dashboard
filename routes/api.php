<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiControllers\LoginController;
use App\Http\Controllers\ApiControllers\PlayerController;

Route::middleware(['api_key'])->prefix('login')->group(function () {
  Route::post('/', [LoginController::class, 'login']);
  Route::post('/pin', [LoginController::class, 'login_pin']);
});

Route::middleware(['api_key_token'])->put('/change-pin', [LoginController::class, 'login_change_pin']);
Route::middleware(['api_key'])->post('/register', [PlayerController::class, 'create']);
Route::middleware(['api_key'])->get('/terms', [PlayerController::class, 'terms']);
Route::middleware(['api_key_token'])->get('/auth', [PlayerController::class, 'get_profile']);
Route::middleware(['api_key_token'])->put('/player/{id}', [PlayerController::class, 'update']);
Route::middleware(['api_key_token'])->post('/step', [PlayerController::class, 'save_log']);

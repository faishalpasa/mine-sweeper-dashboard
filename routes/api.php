<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiControllers\LoginController;
use App\Http\Controllers\ApiControllers\PlayerController;
use App\Http\Controllers\ApiControllers\GameController;

Route::middleware(['api_key'])->prefix('login')->group(function () {
  Route::post('/', [LoginController::class, 'login']);
  Route::post('/pin', [LoginController::class, 'login_pin']);
});

Route::middleware(['api_key_token'])->put('/change-pin', [LoginController::class, 'login_change_pin']);
Route::middleware(['api_key'])->post('/register', [PlayerController::class, 'create']);
Route::middleware(['api_key'])->get('/terms', [PlayerController::class, 'terms']);
Route::middleware(['api_key'])->get('/prize', [GameController::class, 'get_prize']);
Route::middleware(['api_key_token'])->get('/auth', [PlayerController::class, 'get_profile']);
Route::middleware(['api_key_token'])->put('/player/{id}', [PlayerController::class, 'update']);
Route::middleware(['api_key_token'])->post('/step', [PlayerController::class, 'save_log']);
Route::middleware(['api_key'])->get('/step', [PlayerController::class, 'get_log']);
Route::middleware(['api_key'])->get('/data', [PlayerController::class, 'get_data']);
Route::middleware(['api_key_token'])->post('/next-level', [GameController::class, 'next_level']);
Route::middleware(['api_key_token'])->get('/top-score', [GameController::class, 'top_score']);
Route::middleware(['api_key_token'])->get('/winner', [GameController::class, 'winner']);

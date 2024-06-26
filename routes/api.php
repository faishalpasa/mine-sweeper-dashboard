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
Route::middleware(['api_key'])->post('/reset-pin', [LoginController::class, 'login_reset_pin']);
Route::middleware(['api_key'])->post('/pre-register', [PlayerController::class, 'pre_create']);
Route::middleware(['api_key'])->post('/pre-register/check', [PlayerController::class, 'pre_create_check']);
Route::middleware(['api_key'])->get('/msisdn-check/{msisdn}', [PlayerController::class, 'msisdn_check']);
Route::middleware(['api_key'])->post('/validate-token', [PlayerController::class, 'validate_token']);
Route::middleware(['api_key'])->post('/register', [PlayerController::class, 'create']);
Route::middleware(['api_key'])->get('/terms', [PlayerController::class, 'terms']);
Route::middleware(['api_key'])->get('/prize', [GameController::class, 'get_prize']);
Route::middleware(['api_key_token'])->get('/auth', [PlayerController::class, 'get_profile']);
Route::middleware(['api_key_token'])->put('/player/{id}', [PlayerController::class, 'update']);
Route::middleware(['api_key_token'])->post('/step', [PlayerController::class, 'save_log']);
Route::middleware(['api_key'])->get('/step', [PlayerController::class, 'get_log']);
Route::middleware(['api_key'])->get('/data', [PlayerController::class, 'get_data']);
Route::middleware(['api_key_token'])->post('/next-level', [GameController::class, 'next_level']);
Route::middleware(['api_key_token'])->post('/continue-play', [GameController::class, 'continue_play']);
Route::middleware(['api_key'])->get('/top-score', [GameController::class, 'top_score']);
Route::middleware(['api_key'])->get('/winner', [GameController::class, 'winner']);
Route::middleware(['api_key'])->get('/winner/{limit}', [GameController::class, 'winner_limit']);
Route::middleware(['api_key'])->post('/coin/topup', [GameController::class, 'coin_topup']);
Route::middleware(['api_key_token'])->post('/pay/ovo', [GameController::class, 'pay_ovo']);
Route::middleware(['api_key_token'])->get('/pay/ovo/{id}', [GameController::class, 'pay_ovo_check']);
Route::middleware(['api_key_token'])->post('/pay/gopay', [GameController::class, 'pay_gopay']);
Route::middleware(['api_key_token'])->get('/pay/gopay/{id}', [GameController::class, 'pay_gopay_check']);
Route::middleware(['api_key_token'])->post('/message', [GameController::class, 'post_message']);

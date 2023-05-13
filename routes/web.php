<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebControllers\LoginController;
use App\Http\Controllers\WebControllers\DashboardController;
use App\Http\Controllers\WebControllers\ProfileController;
use App\Http\Controllers\WebControllers\PlayerController;
use App\Http\Controllers\WebControllers\TopScoreController;
use App\Http\Controllers\WebControllers\WinnerController;
use App\Http\Controllers\WebControllers\PlayerLogController;
use App\Http\Controllers\WebControllers\CoinPurchaseController;
use App\Http\Controllers\WebControllers\PeriodController;
use App\Http\Controllers\WebControllers\LevelController;
use App\Http\Controllers\WebControllers\PrizeController;
use App\Http\Controllers\WebControllers\TermsController;
use App\Http\Controllers\WebControllers\PaymentMethodController;
use App\Http\Controllers\WebControllers\UserController;
use App\Http\Controllers\WebControllers\MessageController;


Route::prefix('login')->group(function () {
  Route::get('/', [LoginController::class, 'index']);
  Route::post('/', [LoginController::class, 'login']);
});

Route::middleware(['web_auth'])->post('/logout', [LoginController::class, 'logout']);

Route::middleware(['web_auth'])->get('/', [DashboardController::class, 'index']);

Route::middleware(['web_auth'])->prefix('profile')->group(function () {
  Route::get('/', [ProfileController::class, 'index']);
  Route::post('/update', [ProfileController::class, 'post_update']);
});

Route::middleware(['web_auth'])->prefix('player')->group(function () {
  Route::get('/', [PlayerController::class, 'index']);
  Route::get('/create', [PlayerController::class, 'create']);
  Route::post('/create', [PlayerController::class, 'post_create']);
  Route::get('/update/{id}', [PlayerController::class, 'update']);
  Route::get('/update-status/{id}', [PlayerController::class, 'update_status']);
  Route::post('/update-coin/{id}', [PlayerController::class, 'update_coin']);
  Route::post('/update/{id}', [PlayerController::class, 'post_update']);
  Route::get('/delete/{id}', [PlayerController::class, 'delete']);
});

Route::middleware(['web_auth'])->prefix('top-score')->group(function () {
  Route::get('/', [TopScoreController::class, 'index']);
});

Route::middleware(['web_auth'])->prefix('winner')->group(function () {
  Route::get('/', [WinnerController::class, 'index']);
});

Route::middleware(['web_auth'])->prefix('player-log')->group(function () {
  Route::get('/', [PlayerLogController::class, 'index']);
});

Route::middleware(['web_auth'])->prefix('coin-purchase')->group(function () {
  Route::get('/', [CoinPurchaseController::class, 'index']);
  Route::get('/create', [CoinPurchaseController::class, 'create']);
  Route::post('/create', [CoinPurchaseController::class, 'post_create']);
  Route::get('/update/{id}', [CoinPurchaseController::class, 'update']);
  Route::post('/update/{id}', [CoinPurchaseController::class, 'post_update']);
});

Route::middleware(['web_auth'])->prefix('level')->group(function () {
  Route::get('/', [LevelController::class, 'index']);
  Route::get('/create', [LevelController::class, 'create']);
  Route::post('/create', [LevelController::class, 'post_create']);
  Route::get('/update/{id}', [LevelController::class, 'update']);
  Route::post('/update/{id}', [LevelController::class, 'post_update']);
  Route::get('/delete/{id}', [LevelController::class, 'delete']);
});

Route::middleware(['web_auth'])->prefix('payment-method')->group(function () {
  Route::get('/', [PaymentMethodController::class, 'index']);
  Route::get('/create', [PaymentMethodController::class, 'create']);
  Route::post('/create', [PaymentMethodController::class, 'post_create']);
  Route::get('/update/{id}', [PaymentMethodController::class, 'update']);
  Route::post('/update/{id}', [PaymentMethodController::class, 'post_update']);
});

Route::middleware(['web_auth'])->prefix('prize')->group(function () {
  Route::get('/', [PrizeController::class, 'index']);
  Route::get('/create', [PrizeController::class, 'create']);
  Route::post('/create', [PrizeController::class, 'post_create']);
  Route::get('/update/{id}', [PrizeController::class, 'update']);
  Route::post('/update/{id}', [PrizeController::class, 'post_update']);
  Route::get('/delete/{id}', [PrizeController::class, 'delete']);
});

Route::middleware(['web_auth'])->prefix('period')->group(function () {
  Route::get('/', [PeriodController::class, 'index']);
  Route::get('/create', [PeriodController::class, 'create']);
  Route::post('/create', [PeriodController::class, 'post_create']);
  Route::get('/update/{id}', [PeriodController::class, 'update']);
  Route::post('/update/{id}', [PeriodController::class, 'post_update']);
});

Route::middleware(['web_auth'])->prefix('terms')->group(function () {
  Route::get('/', [TermsController::class, 'index']);
  Route::get('/create', [TermsController::class, 'create']);
  Route::post('/create', [TermsController::class, 'post_create']);
  Route::get('/update/{id}', [TermsController::class, 'update']);
  Route::post('/update/{id}', [TermsController::class, 'post_update']);
  Route::get('/delete/{id}', [TermsController::class, 'delete']);
});

Route::middleware(['web_auth'])->prefix('payment-method')->group(function () {
  Route::get('/', [PaymentMethodController::class, 'index']);
  Route::get('/create', [PaymentMethodController::class, 'create']);
  Route::post('/create', [PaymentMethodController::class, 'post_create']);
  Route::get('/update/{id}', [PaymentMethodController::class, 'update']);
  Route::post('/update/{id}', [PaymentMethodController::class, 'post_update']);
  Route::get('/delete/{id}', [PaymentMethodController::class, 'delete']);
});

Route::middleware(['web_auth'])->prefix('user')->group(function () {
  Route::get('/', [UserController::class, 'index']);
  Route::get('/create', [UserController::class, 'create']);
  Route::post('/create', [UserController::class, 'post_create']);
  Route::get('/update/{id}', [UserController::class, 'update']);
  Route::post('/update/{id}', [UserController::class, 'post_update']);
  Route::get('/delete/{id}', [UserController::class, 'delete']);
});

Route::middleware(['web_auth'])->prefix('message')->group(function () {
  Route::get('/', [MessageController::class, 'index']);
  Route::get('/create', [MessageController::class, 'create']);
  Route::post('/create', [MessageController::class, 'post_create']);
  Route::get('/update/{id}', [MessageController::class, 'update']);
  Route::post('/update/{id}', [MessageController::class, 'post_update']);
});

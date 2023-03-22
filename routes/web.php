<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TopScoreController;
use App\Http\Controllers\WinnerController;
use App\Http\Controllers\PlayerLogController;
use App\Http\Controllers\CoinPurchaseController;
use App\Http\Controllers\PrizeController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\PaymentMethodController;


Route::get('/', [DashboardController::class, 'index']);

Route::prefix('profile')->group(function () {
  Route::get('/', [ProfileController::class, 'index']);
});

Route::prefix('player')->group(function () {
  Route::get('/', [PlayerController::class, 'index']);
});

Route::prefix('top-score')->group(function () {
  Route::get('/', [TopScoreController::class, 'index']);
});

Route::prefix('winner')->group(function () {
  Route::get('/', [WinnerController::class, 'index']);
});

Route::prefix('player-log')->group(function () {
  Route::get('/', [PlayerLogController::class, 'index']);
});

Route::prefix('coin-purchase')->group(function () {
  Route::get('/', [CoinPurchaseController::class, 'index']);
});

Route::prefix('prize')->group(function () {
  Route::get('/', [PrizeController::class, 'index']);
});

Route::prefix('terms')->group(function () {
  Route::get('/', [TermsController::class, 'index']);
});

Route::prefix('payment-method')->group(function () {
  Route::get('/', [PaymentMethodController::class, 'index']);
});

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
use App\Http\Controllers\WebControllers\PrizeController;
use App\Http\Controllers\WebControllers\TermsController;
use App\Http\Controllers\WebControllers\PaymentMethodController;


Route::prefix('login')->group(function () {
  Route::get('/', [LoginController::class, 'index']);
  Route::post('/', [LoginController::class, 'login']);
});

Route::middleware(['web_auth'])->post('/logout', [LoginController::class, 'logout']);

Route::middleware(['web_auth'])->get('/', [DashboardController::class, 'index']);

Route::middleware(['web_auth'])->prefix('profile')->group(function () {
  Route::get('/', [ProfileController::class, 'index']);
  Route::post('/update', [ProfileController::class, 'update_profile']);
});

Route::middleware(['web_auth'])->prefix('player')->group(function () {
  Route::get('/', [PlayerController::class, 'index']);
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
});

Route::middleware(['web_auth'])->prefix('prize')->group(function () {
  Route::get('/', [PrizeController::class, 'index']);
});

Route::middleware(['web_auth'])->prefix('terms')->group(function () {
  Route::get('/', [TermsController::class, 'index']);
});

Route::middleware(['web_auth'])->prefix('payment-method')->group(function () {
  Route::get('/', [PaymentMethodController::class, 'index']);
});

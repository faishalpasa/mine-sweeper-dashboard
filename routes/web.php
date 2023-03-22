<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TopScoreController;
use App\Http\Controllers\WinnerController;
use App\Http\Controllers\PrizeController;
use App\Http\Controllers\TermsController;


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

Route::prefix('prize')->group(function () {
  Route::get('/', [PrizeController::class, 'index']);
});

Route::prefix('terms')->group(function () {
  Route::get('/', [TermsController::class, 'index']);
});

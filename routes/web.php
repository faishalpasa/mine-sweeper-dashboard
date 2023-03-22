<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TopScoreController;
use App\Http\Controllers\WinnerController;


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

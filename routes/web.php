<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlayerController;


Route::get('/', [DashboardController::class, 'index']);

Route::prefix('profile')->group(function () {
  Route::get('/', [ProfileController::class, 'index']);
});

Route::prefix('player')->group(function () {
  Route::get('/', [PlayerController::class, 'index']);
});

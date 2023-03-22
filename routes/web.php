<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;


Route::get('/', [DashboardController::class, 'index']);

Route::prefix('profile')->group(function () {
  Route::get('/', [ProfileController::class, 'index']);
});

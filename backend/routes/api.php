<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserWinController;
use App\Http\Controllers\Api\SpinController;
use App\Http\Controllers\Api\UserController;

// User Win routes
Route::apiResource('user-wins', UserWinController::class);
Route::get('/user/{userId}/wins', [UserWinController::class, 'getUserWins']);

// Other routes
Route::get('/prizes', [SpinController::class, 'prizes']);  
Route::post('/spin', [SpinController::class, 'spin']);     
Route::post('/store-spin', [SpinController::class, 'store']); 
Route::get('/spins', [SpinController::class, 'index']);

Route::post('/store-user', [UserController::class, 'store']);
Route::get('/users', [UserController::class, 'index']);
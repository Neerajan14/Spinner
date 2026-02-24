<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SpinController;
use App\Http\Controllers\Api\UserController;

Route::get('/prizes', [SpinController::class, 'prizes']);  
Route::post('/spin', [SpinController::class, 'spin']);     
Route::post('/store-spin', [SpinController::class, 'store']); 
Route::get('/spins', [SpinController::class, 'index']);

// User routes
Route::post('/store-user', [UserController::class, 'store']); // Save user
Route::get('/users', [UserController::class, 'index']);       // Fetch all users

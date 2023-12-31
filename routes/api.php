<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->resource('expense', ExpenseController::class);

Route::resource('users', UserController::class);

Route::prefix('users')->group(function () {
    Route::post('singup', [UserController::class,'singUp']);
    Route::post('singin', [UserController::class,'singIn']);
});

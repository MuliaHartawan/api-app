<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', [App\Http\Controllers\Auth\UserController::class, 'login']);
Route::post('/register', [App\Http\Controllers\Auth\UserConroller::class, 'register']);
Route::post('/logout', [App\Http\Controllers\Auth\UserConroller::class, 'logout'])->middleware('auth:api');

Route::middleware(['auth:api'])->group(function () {
    Route::resource('product', ProductController::class);
    Route::resource('transaction', TransactionController::class);
});

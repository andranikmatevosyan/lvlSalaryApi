<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SalаryController;
use Illuminate\Support\Facades\Route;

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
Route::post('login', [AuthController::class, 'signIn'])->name('login');
Route::post('register', [AuthController::class, 'signUp'])->name('register');

Route::middleware('auth:sanctum')->group( function () {
    Route::group(['prefix' => 'salary'], function () {
        Route::post('calculate', [SalаryController::class, 'calculate'])->name('salary.calculate');
        Route::post('save', [SalаryController::class, 'save'])->name('salary.save');
    });
});

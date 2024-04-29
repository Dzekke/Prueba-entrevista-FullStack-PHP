<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use \App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return view('welcome');
});


Route::group(['middleware' => [EnsureTokenIsValid::class]], function () {
Route::get('/customers',[CustomerController::class,'index']);
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CustomerController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login',[LoginController::class,'login']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/customer/create',[CustomerController::class,'newCustomer']); 
Route::delete('/customer/delete/{dni}',[CustomerController::class,'deleteCustomer']);
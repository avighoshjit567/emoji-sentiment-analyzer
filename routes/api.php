<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\SentimentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:api')->group( function () {
    Route::resource('result', SentimentController::class);
    Route::post('analysis-store', [SentimentController::class, 'Store']);
});

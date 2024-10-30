<?php

use App\Http\Controllers\OpenIDController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/token', [AccessTokenController::class, 'issueToken'])->middleware('throttle');

Route::middleware('auth:api')->get('/userinfo', function (Request $request) {
    return response()->json([
        'sub' => $request->user()->id,
        'name' => $request->user()->name,
        'email' => $request->user()->email,
    ]);
});


Route::middleware('auth:api')->get('/id-token', [OpenIDController::class, 'issueIDToken']);

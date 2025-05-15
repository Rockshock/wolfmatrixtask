<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController as CategoryApiController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
    Route::get('categories', [CategoryApiController::class, 'index']);
    Route::post('categories', [CategoryApiController::class, 'store']);
    Route::put('categories/{id}', [CategoryApiController::class, 'update']);
    Route::delete('categories/{id}', [CategoryApiController::class, 'destroy']);
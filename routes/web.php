<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\EnsureAuthenticated;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\RedirectIfAuthenticated;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::middleware([RedirectIfAuthenticated::class])->group(function () {
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware([EnsureAuthenticated::class])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
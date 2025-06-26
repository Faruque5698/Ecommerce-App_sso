<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SsoController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/redirect', [SsoController::class, 'redrictToSso'])->name('login');

Route::get('/callback', [SsoController::class, 'callback'])->name('callback');

Route::group(['middleware' => ['web', 'auth', 'throttle:10,1']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [SsoController::class, 'logout'])->name('logout');
});


 
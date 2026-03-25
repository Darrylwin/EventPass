<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LogoutController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'admin' => redirect('/admin'),
        'organisateur' => redirect()->route('organisateur.dashboard'),
        default => redirect()->route('participant.dashboard'),
    };
})->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'role:organisateur'])
    ->prefix('organisateur')
    ->name('organisateur.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('organisateur.dashboard'))->name('dashboard');
    });

Route::middleware(['auth', 'role:participant'])
    ->prefix('participant')
    ->name('participant.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('participant.dashboard'))->name('dashboard');
    });

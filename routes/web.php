<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Pages publiques
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentification (invités uniquement)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Déconnexion
Route::post('/logout', [LogoutController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Dashboard — redirige selon le rôle
Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'organisateur' => redirect()->route('organisateur.dashboard'),
        default => redirect()->route('participant.dashboard'),
    };
})->middleware('auth')->name('dashboard');

// Zone admin
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    });

// Zone organisateur
Route::middleware(['auth', 'role:organisateur'])
    ->prefix('organisateur')
    ->name('organisateur.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('organisateur.dashboard'))->name('dashboard');
    });

// Zone participant
Route::middleware(['auth', 'role:participant'])
    ->prefix('participant')
    ->name('participant.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('participant.dashboard'))->name('dashboard');
    });

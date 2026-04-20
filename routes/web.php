<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Organisateur\EventController;
use App\Http\Controllers\Organisateur\RegistrationController;
use App\Http\Controllers\Participant\RegistrationController as ParticipantRegistrationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [ParticipantRegistrationController::class, 'publicIndex'])->name('home');

Route::get('/home', fn () => redirect()->route('dashboard'));
Route::get('/events', [ParticipantRegistrationController::class, 'publicIndex'])->name('events.index');
Route::get('/events/{event}', [ParticipantRegistrationController::class, 'publicShow'])->name('events.show');

Route::get('/ux-answers', function () {
    return view('ux_answers');
})->name('ux.answers');

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
    return match (Auth::user()->role) {
        'admin' => redirect('/admin'),
        'organisateur' => redirect()->route('organisateur.dashboard'),
        default => redirect()->route('participant.dashboard'),
    };
})->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'role:organisateur'])
    ->prefix('organisateur')
    ->name('organisateur.')
    ->group(function () {
        Route::get('/dashboard', [EventController::class, 'dashboard'])->name('dashboard');
        Route::resource('events', EventController::class);

        Route::patch('/registrations/{registration}/invalidate', [RegistrationController::class, 'invalidate'])
            ->name('registrations.invalidate');
        Route::patch('/registrations/{registration}/reactivate', [RegistrationController::class, 'reactivate'])
            ->name('registrations.reactivate');
    });

Route::middleware(['auth', 'role:participant'])
    ->prefix('participant')
    ->name('participant.')
    ->group(function () {
        Route::get('/dashboard', [ParticipantRegistrationController::class, 'index'])->name('dashboard');
        Route::get('/events', [ParticipantRegistrationController::class, 'publicIndex'])->name('events.index');
        Route::post('/events/{event}/register', [ParticipantRegistrationController::class, 'store'])->name('events.register');
    });

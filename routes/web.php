<?php

use App\Http\Controllers\Organisateur\EventController;
use App\Http\Controllers\Organisateur\RegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Participant\RegistrationController as ParticipantRegistrationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // je redirige temporairement vers la page de login, mais on doit normalement afficher ici la page d'accueil.
    // je passe cette main au dev chargé de faire la page d'accueil ainis que les différentes pages UI/UX
    return redirect()->route('login');
})->name('home');

Route::get('/home', fn() => redirect()->route('dashboard'));
Route::get('/events', [ParticipantRegistrationController::class, 'publicIndex'])->name('events.index');

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
    $role = Auth::user()?->role;

    if (!$role) {
        return redirect()->route('login');
    }

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

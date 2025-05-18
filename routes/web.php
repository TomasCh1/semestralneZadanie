<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Guest Routes (neprihlásený používateľ)
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {
    // Prihlásenie
    Route::view('login', 'auth.login')->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Registrácia
    Route::view('register', 'auth.register')->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (registrovaný používateľ)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Dashboard po prihlásení
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Testy (prístupné len registrovaným)
    Route::get('test', [TestController::class, 'showTestPage'])->name('test.show');
    Route::post('test/submit', [TestController::class, 'submitAnswer'])->name('test.submit');

    // Profil
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Odhlásenie
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (administrátor)
|--------------------------------------------------------------------------
| Vlastné middleware 'is_admin' overí user->role_id === 1
*/
Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin Dashboard
        Route::view('dashboard', 'admin.dashboard')->name('dashboard');
        // ... ďalšie adminské routy
    });

// Laravel-ské routy pre password reset, email verification a pod.
require __DIR__ . '/auth.php';

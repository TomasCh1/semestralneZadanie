<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestHistoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestRunController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\GuestTestController;

Route::prefix('guest-test')->name('guest.')->group(function () {
    // /guest-test          → vyplnenie 15 náhodných otázok
    Route::get('/',           [GuestTestController::class, 'start'   ])->name('start');
    // /guest-test/finished   → súhrn výsledkov z localStorage
    Route::get('/finished',   [GuestTestController::class, 'finished'])->name('finished');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // prihlásený používateľ → zobrazí dashboard.blade.php
    if (Auth::check()) {
        return view('dashboard');
    }

    // hosť → okamžité presmerovanie na guest test
    return redirect()->route('guest.start');
})->name('dashboard');

Route::get('/TestHistory', [TestHistoryController::class, 'index'])->name('TestHistory');

Route::view('/dashboard', 'dashboard')->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::view('/', 'auth.index');
});

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Create test run
    Route::post('/test-runs/start', [TestRunController::class, 'store'])
        ->name('test-runs.start');

});

Route::get('locale/{locale}', [LocaleController::class, 'switch'])
    ->name('locale.switch');

Route::middleware('web')->group(function () {

    // zobraziť aktuálnu otázku (alebo výsledky)
    Route::get ('/tests/{run}/question', [TestController::class, 'show'])
        ->name('tests.question');

    // spracovať odpoveď a posunúť sa ďalej
    Route::post('/tests/{run}/answer',  [TestController::class, 'answer'])
        ->name('tests.answer');
});

require __DIR__.'/auth.php';

//Admin Routes

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {
        // List all test runs
        Route::get('history', [HistoryController::class, 'index'])
            ->name('history.index');

        // Export history to CSV
        Route::get('history/export', [HistoryController::class, 'export'])
            ->name('history.export');

        // Show a single test run (so GET /admin/history/{run} is allowed)
        Route::get('history/{userId}',       [HistoryController::class, 'show'])
            ->name('history.show');
        Route::get('/history/{userId}/run/{runId}', [HistoryController::class, 'details'])
            ->name('history.details');

        // Delete a single record
        Route::delete('history/{run}', [HistoryController::class, 'destroy'])
            ->name('history.destroy');
    });
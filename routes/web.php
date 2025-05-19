<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestRunController;
use App\Http\Controllers\TestController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::view('/', 'auth.index');
    Route::view('dashboard', 'dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Create test run
    Route::post('/test-runs/start', [TestRunController::class, 'store'])
        ->name('test-runs.start');

});


Route::middleware('web')->group(function () {
    // zobraziť aktuálnu otázku (alebo výsledky)
    Route::get ('/tests/{run}/question', [TestController::class, 'show'])
        ->name('tests.question');

    // spracovať odpoveď a posunúť sa ďalej
    Route::post('/tests/{run}/answer',  [TestController::class, 'answer'])
        ->name('tests.answer');
});

require __DIR__.'/auth.php';

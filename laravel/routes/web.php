<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);

});

Route::get('/newlobby', function () {
    return Inertia::render('newlobby');
});

Route::get('/imposter', function () {
    return Inertia::render('imposter');
});

Route::get('/votingscreen', function () {
    return Inertia::render('votingscreen');
});

Route::get('/chatscreen', function () {
    return Inertia::render('chatscreen');
});

Route::get('/joinlobby', function () {
    return Inertia::render('joinlobby');
});

Route::get('/promptscreen', function () {
    return Inertia::render('promptscreen');
});

Route::get('/waiting', function () {
    return Inertia::render('waiting');
});

Route::get('/Help', function () {
    return Inertia::render('Help');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

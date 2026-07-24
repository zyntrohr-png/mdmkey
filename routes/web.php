<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mail', function () {
        return view('mail.index');
    })->name('mail');

    Route::get('/dashboard', function () {
        return redirect()->route('mail');
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});

require __DIR__.'/auth.php';

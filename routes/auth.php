<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware('guest')->group(function () {
    Route::get('register', App\Livewire\Auth\Register::class)->name('register');
    Route::get('login', App\Livewire\Auth\Login::class)->name('login');
});


Route::middleware('auth')->group(function () {
    Route::post('logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        session()->flash('logout_success', true);
        return redirect()->route('login', ['navigate' => true]);
    })->name('logout');
});

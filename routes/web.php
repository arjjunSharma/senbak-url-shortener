<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');


    // Invitations
    Route::get('/invitations/create', [InvitationController::class, 'create'])
        ->name('invitations.create');
    Route::post('/invitations', [InvitationController::class, 'store'])
        ->name('invitations.store');

    // Short URLs
    Route::get('/short-urls', [ShortUrlController::class, 'index'])
        ->name('short-urls.index');
    Route::post('/short-urls', [ShortUrlController::class, 'store'])
        ->name('short-urls.store');

    // Resolver is NOT public
    Route::get('/r/{code}', [ShortUrlController::class, 'resolve'])
        ->name('short-urls.resolve');
});

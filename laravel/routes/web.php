<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ApplicationController::class, 'dashboard'])->name('dashboard');
    
    // Application
    Route::get('/application/create', [ApplicationController::class, 'create'])->name('application.create');
    Route::post('/application/store', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/application/{application}/edit', [ApplicationController::class, 'edit'])->name('application.edit');
    Route::put('/application/{application}', [ApplicationController::class, 'update'])->name('application.update');
    Route::get('/application/{application}/preview', [ApplicationController::class, 'preview'])->name('application.preview');
    Route::post('/application/{application}/submit', [ApplicationController::class, 'submit'])->name('application.submit');
    
    // Admin Routes
    Route::middleware(['can:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
        Route::get('/export', [AdminController::class, 'export'])->name('export');
        Route::get('/application/{application}', [AdminController::class, 'show'])->name('application.show');
    });
});

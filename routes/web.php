<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:super-admin|admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('roles', RoleController::class)->only(['index', 'edit', 'update'])->middleware('permission:manage roles');
});

require __DIR__.'/auth.php';

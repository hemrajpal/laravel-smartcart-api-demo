<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminCategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['admin.guest'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

});

Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('admin.user')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');

    Route::resource('categories', AdminCategoryController::class)->except(['show']);
});
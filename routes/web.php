<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Categories\{
    IndexCategoryController,
    StoreCategoryController,
    ShowCategoryController,
    UpdateCategoryController,
    DestroyCategoryController,
};

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/categories/{category}', ShowCategoryController::class)->name('categories.show');
    Route::get('/categories', IndexCategoryController::class)->name('categories.index');
    Route::post('/categories', StoreCategoryController::class)
        ->middleware('permission:categories.create')
        ->name('categories.store');
    Route::put('/categories/{category}', UpdateCategoryController::class)
        ->middleware('permission:categories.edit')
        ->name('categories.update');
    Route::delete('/categories/{category}', DestroyCategoryController::class)
        ->middleware('permission:categories.delete')
        ->name('categories.destroy');

    Route::view('/pos', 'dashboard')->name('pos.index');
    Route::view('/products', 'dashboard')->name('products.index');
    Route::view('/users', 'dashboard')->name('users.index');
    Route::view('/roles', 'dashboard')->name('roles.index');
    Route::view('/transactions', 'dashboard')->name('transactions.index');
    Route::view('/reports', 'dashboard')->name('reports.index');

});
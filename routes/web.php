<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/categories', function () {
        return view('categories.index', [
            'categories' => Category::withCount('products')->paginate(10),
        ]);
    })->name('categories.index');

    // TODO: ganti satu-satu jadi controller asli begitu modulnya dikerjakan
    Route::view('/pos', 'dashboard')->name('pos.index');
    Route::view('/products', 'dashboard')->name('products.index');
    Route::view('/users', 'dashboard')->name('users.index');
    Route::view('/roles', 'dashboard')->name('roles.index');
    Route::view('/transactions', 'dashboard')->name('transactions.index');
    Route::view('/reports', 'dashboard')->name('reports.index');

});
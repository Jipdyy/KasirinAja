<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/categories', function () {
    return view('categories.index', [
        'categories' => Category::withCount('products')->paginate(10),
    ]);
})->name('categories.index');

Route::view('/dashboard', 'components.layouts.app')->name('dashboard');
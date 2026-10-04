<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('dev-preview')->group(function () {
    Route::view('/guest', 'dev-preview.guest');
    Route::view('/app', 'dev-preview.app');
    Route::view('/print', 'dev-preview.print');
    Route::view('/navbar', 'dev-preview.navbar');
});
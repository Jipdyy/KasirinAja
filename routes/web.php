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
use App\Http\Controllers\Products\{
    IndexProductController,
    StoreProductController,
    ShowProductController,
    UpdateProductController,
    DestroyProductController,
};
use App\Http\Controllers\Users\{
    IndexUserController,
    ShowUserController,
    StoreUserController,
    UpdateUserController,
    ToggleUserStatusController,
    ResetUserPasswordController,
};
use App\Http\Controllers\Roles\{
    IndexRoleController,
    ShowRoleController,
    StoreRoleController,
    UpdateRoleController,
    DestroyRoleController,
};
use App\Http\Controllers\Pos\{
    IndexPosController,
    StoreCartItemController,
    UpdateCartItemController,
    DestroyCartItemController,
    DestroyCartController,
    StoreTransactionController,
};
use App\Http\Controllers\Transactions\{
    IndexTransactionController, 
    ShowTransactionController
};
use App\Http\Controllers\DashboardController;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    //user
    Route::get('/users', IndexUserController::class)
        ->middleware('permission:users.view')
        ->name('users.index');

    Route::get('/users/{user}', ShowUserController::class)
        ->middleware('permission:users.view')
        ->name('users.show');

    Route::post('/users', StoreUserController::class)
        ->middleware('permission:users.create')
        ->name('users.store');

    Route::put('/users/{user}', UpdateUserController::class)
        ->middleware('permission:users.edit')
        ->name('users.update');

    Route::patch('/users/{user}/status', ToggleUserStatusController::class)
        ->middleware('permission:users.deactivate')
        ->name('users.status');

    Route::patch('/users/{user}/password', ResetUserPasswordController::class) 
        ->middleware('permission:users.reset-password') 
        ->name('users.password');

    //roles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', IndexRoleController::class)->name('index');
        Route::get('/{role}', ShowRoleController::class)->name('show');
        Route::post('/', StoreRoleController::class)->name('store');
        Route::put('/{role}', UpdateRoleController::class)->name('update');
        Route::delete('/{role}', DestroyRoleController::class)->name('destroy');
    });

    //categories
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

    //products
    Route::get('/products', IndexProductController::class)->name('products.index');
    Route::get('/products/{product}', ShowProductController::class)->name('products.show');
    Route::post('/products', StoreProductController::class)->name('products.store');
    Route::put('/products/{product}', UpdateProductController::class)->name('products.update');
    Route::delete('/products/{product}', DestroyProductController::class)->name('products.destroy');

    //POS
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', IndexPosController::class)->name('index');
        Route::post('/cart/{product}', StoreCartItemController::class)->name('cart.store');
        Route::patch('/cart/{product}', UpdateCartItemController::class)->name('cart.update');
        Route::delete('/cart/{product}', DestroyCartItemController::class)->name('cart.destroy');
        Route::delete('/cart', DestroyCartController::class)->name('cart.clear');
        Route::post('/checkout', StoreTransactionController::class)->name('checkout');
    });
    Route::delete('/cart/{product}', DestroyCartItemController::class)->name('cart.destroy')->withTrashed();

    //transactions
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', IndexTransactionController::class)->name('index');
        Route::get('/{transaction}', ShowTransactionController::class)->name('show');
    });

});
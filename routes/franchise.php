<?php

use App\Http\Controllers\Franchise\DashboardController;
use App\Http\Controllers\Franchise\ProductController;
use App\Http\Controllers\Franchise\OrderController;
use App\Http\Controllers\Franchise\ProfileController;

Route::group(['prefix' => 'franchise', 'middleware' => ['auth', 'franchise', 'prevent-back-history'], 'as' => 'franchise.'], function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index')->name('products');
        Route::get('/product/create', 'create')->name('products.create');
        Route::post('/products/store', 'store')->name('products.store');
        Route::get('/product/{id}/edit', 'edit')->name('products.edit');
        Route::post('/products/update/{product}', 'update')->name('products.update');
        Route::get('/products/destroy/{id}', 'destroy')->name('products.destroy');
    });

    // Categories
    Route::controller(App\Http\Controllers\Franchise\CategoryController::class)->group(function () {
        Route::get('/categories', 'index')->name('categories.index');
        Route::get('/categories/create', 'create')->name('categories.create');
        Route::post('/categories/store', 'store')->name('categories.store');
        Route::get('/categories/{id}/edit', 'edit')->name('categories.edit');
        Route::post('/categories/update/{id}', 'update')->name('categories.update');
        Route::get('/categories/destroy/{id}', 'destroy')->name('categories.destroy');
    });

    // Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::post('/profile/update/{id}', 'update')->name('profile.update');
    });

    // Orders
    Route::controller(App\Http\Controllers\Franchise\OrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('orders.index');
        Route::get('/orders/{id}/show', 'show')->name('orders.show');
        Route::post('/orders/update_delivery_status', 'update_delivery_status')->name('orders.update_delivery_status');
        Route::post('/orders/update_payment_status', 'update_payment_status')->name('orders.update_payment_status');
    });

    // Sub-Franchises
    Route::controller(App\Http\Controllers\Franchise\SubFranchiseController::class)->group(function () {
        Route::get('/sub-franchises', 'index')->name('sub_franchises.index');
        Route::get('/sub-franchises/create', 'create')->name('sub_franchises.create');
        Route::post('/sub-franchises/store', 'store')->name('sub_franchises.store');
    });

});

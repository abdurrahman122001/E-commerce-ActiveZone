<?php

use App\Http\Controllers\Franchise\DashboardController;
use App\Http\Controllers\Franchise\FranchiseEmployeeController;
use App\Http\Controllers\Franchise\ProductController;
use App\Http\Controllers\Franchise\OrderController;
use App\Http\Controllers\Franchise\ProfileController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\Franchise\Employee\SupportTicketController as EmployeeSupportTicketController;

Route::group(['prefix' => 'franchise', 'middleware' => ['auth', 'franchise', 'prevent-back-history'], 'as' => 'franchise.'], function () {
    
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/sales-report', 'sales_report')->name('sales_report');
    });

    // Products
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index')->name('products');
        Route::get('/product/create', 'create')->name('products.create');
        Route::post('/products/store', 'store')->name('products.store');
        Route::get('/product/{id}/edit', 'edit')->name('products.edit');
        Route::post('/products/update/{product}', 'update')->name('products.update');
        Route::get('/products/destroy/{id}', 'destroy')->name('products.destroy');
        Route::post('/products/published', 'updatePublished')->name('products.published');
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

    // Verification
    Route::post('/verification-info-update', [App\Http\Controllers\FranchiseController::class, 'updateVerificationInfo'])->name('verification_info_update');

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
        Route::post('/sub-franchises/set-commission', 'set_commission')->name('sub_franchises.set_commission');
        Route::get('/sub-franchises/login/{id}', 'login')->name('sub_franchises.login');
    });

    // Franchise Employees
    Route::controller(FranchiseEmployeeController::class)->group(function () {
        Route::get('/employees', 'index')->name('employees.index');
        Route::get('/employees/create', 'create')->name('employees.create');
        Route::post('/employees/store', 'store')->name('employees.store');
        Route::get('/employees/{id}/edit', 'edit')->name('employees.edit');
        Route::post('/employees/update/{id}', 'update')->name('employees.update');
        Route::get('/employees/destroy/{id}', 'destroy')->name('employees.destroy');
    });

    // Franchise Vendors
    Route::controller(VendorController::class)->group(function () {
        Route::get('/vendors', 'index')->name('vendors.index');
        Route::get('/vendors/create', 'create')->name('vendors.create');
        Route::post('/vendors/store', 'store')->name('vendors.store');
        Route::get('/vendors/edit/{id}', 'edit')->name('vendors.edit');
        Route::post('/vendors/update/{id}', 'update')->name('vendors.update');
        Route::get('/vendors/commission-history', 'commissionHistory')->name('vendors.commission_history');
    });

    // Delivery Boys
    Route::controller(App\Http\Controllers\Franchise\DeliveryBoyController::class)->group(function () {
        Route::get('/delivery-boys', 'index')->name('delivery_boys.index');
        Route::get('/delivery-boys/create', 'create')->name('delivery_boys.create');
        Route::post('/delivery-boys/store', 'store')->name('delivery_boys.store');
        Route::get('/delivery-boys/{id}/edit', 'edit')->name('delivery_boys.edit');
        Route::post('/delivery-boys/update/{id}', 'update')->name('delivery_boys.update');
    });

    // Support Tickets
    Route::controller(SupportTicketController::class)->group(function () {
        Route::get('/support-tickets', 'index')->name('support_tickets.index');
        Route::post('/support-tickets/store', 'store')->name('support_tickets.store');
        Route::get('/support-tickets/show/{id}', 'show')->name('support_tickets.show');
        Route::post('/support-tickets/reply', 'seller_store')->name('support_tickets.reply');
    });

    // Withdraw Requests
    Route::controller(App\Http\Controllers\CommissionWithdrawController::class)->group(function () {
        Route::get('/withdraw-requests', 'index')->name('withdraw_requests');
        Route::post('/withdraw-requests/store', 'store')->name('withdraw_requests.store');
    });

});
// Franchise Employee Routes (Unified Login)
Route::group(['prefix' => 'franchise-employee', 'as' => 'franchise.employee.'], function () {
    Route::get('/login', [App\Http\Controllers\Franchise\Employee\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Franchise\Employee\LoginController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Franchise\Employee\LoginController::class, 'logout'])->name('logout');
    Route::get('/logout', [App\Http\Controllers\Franchise\Employee\LoginController::class, 'logout'])->name('logout.get');

    Route::group(['middleware' => ['franchise_employee', 'prevent-back-history']], function () {
        Route::get('/dashboard', [App\Http\Controllers\Franchise\Employee\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/payouts', [App\Http\Controllers\Franchise\Employee\DashboardController::class, 'payouts'])->name('payouts');
        Route::get('/sales-report', [App\Http\Controllers\Franchise\Employee\DashboardController::class, 'sales_report'])->name('sales_report');
        
        // Profile
        Route::controller(App\Http\Controllers\Franchise\Employee\ProfileController::class)->group(function () {
            Route::get('/profile', 'index')->name('profile.index');
            Route::post('/profile/update', 'update')->name('profile.update');
        });
        
        // Vendor registration for employees
        Route::get('/vendors', [App\Http\Controllers\VendorController::class, 'index'])->name('vendors.index');
        Route::get('/vendors/create', [App\Http\Controllers\VendorController::class, 'create'])->name('vendors.create');
        Route::post('/vendors/store', [App\Http\Controllers\VendorController::class, 'store'])->name('vendors.store');
        Route::get('/vendors/edit/{id}', [App\Http\Controllers\VendorController::class, 'edit'])->name('vendors.edit');
        Route::post('/vendors/update/{id}', [App\Http\Controllers\VendorController::class, 'update'])->name('vendors.update');

        // Product Management for employees
        Route::controller(App\Http\Controllers\Franchise\ProductController::class)->group(function () {
            Route::get('/products', 'index')->name('products');
            Route::get('/product/create', 'create')->name('products.create');
            Route::post('/products/store', 'store')->name('products.store');
            Route::get('/product/{id}/edit', 'edit')->name('products.edit');
            Route::post('/products/update/{product}', 'update')->name('products.update');
            Route::get('/products/destroy/{id}', 'destroy')->name('products.destroy');
            Route::post('/products/published', 'updatePublished')->name('products.published');
        });

        // Category Management for employees
        Route::controller(App\Http\Controllers\Franchise\CategoryController::class)->group(function () {
            Route::get('/categories', 'index')->name('categories.index');
            Route::get('/categories/create', 'create')->name('categories.create');
            Route::post('/categories/store', 'store')->name('categories.store');
            Route::get('/categories/{id}/edit', 'edit')->name('categories.edit');
            Route::post('/categories/update/{id}', 'update')->name('categories.update');
            Route::get('/categories/destroy/{id}', 'destroy')->name('categories.destroy');
        });

        // Delivery Boys for employees
        Route::controller(App\Http\Controllers\Franchise\DeliveryBoyController::class)->group(function () {
            Route::get('/delivery-boys', 'index')->name('delivery_boys.index');
            Route::get('/delivery-boys/create', 'create')->name('delivery_boys.create');
            Route::post('/delivery-boys/store', 'store')->name('delivery_boys.store');
            Route::get('/delivery-boys/{id}/edit', 'edit')->name('delivery_boys.edit');
            Route::post('/delivery-boys/update/{id}', 'update')->name('delivery_boys.update');
        });

        // Support Tickets for employees (uses admin-side show route)
        Route::controller(EmployeeSupportTicketController::class)->group(function () {
            Route::get('/support-tickets', 'index')->name('support_tickets.index');
            Route::post('/support-tickets/store', 'store')->name('support_tickets.store');
            Route::get('/support-tickets/show/{id}', 'show')->name('support_tickets.show');
            Route::post('/support-tickets/reply', 'reply')->name('support_tickets.reply');
        });

        // Withdraw Requests for employees
        Route::controller(App\Http\Controllers\CommissionWithdrawController::class)->group(function () {
            Route::get('/withdraw-requests', 'employee_index')->name('withdraw_requests');
            Route::post('/withdraw-requests/store', 'store')->name('withdraw_requests.store');
        });
    });
});

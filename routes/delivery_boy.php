<?php

/*
|--------------------------------------------------------------------------
| POS Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Admin

use App\Http\Controllers\DeliveryBoyController;
use App\Http\Controllers\OrderController;

Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin', 'prevent-back-history']], function(){
    //Delivery Boy
    Route::resource('delivery-boys', DeliveryBoyController::class);
    
    Route::controller(DeliveryBoyController::class)->group(function () {
        Route::get('/delivery-boy/ban/{id}', 'ban')->name('delivery-boy.ban');
        Route::get('/delivery-boy-configuration', 'delivery_boy_configure')->name('delivery-boy-configuration');
        Route::post('/delivery-boy/order-collection', 'order_collection_form')->name('delivery-boy.order-collection');
        Route::post('/collection-from-delivery-boy', 'collection_from_delivery_boy')->name('collection-from-delivery-boy');
        Route::post('/delivery-boy/delivery-earning', 'delivery_earning_form')->name('delivery-boy.delivery-earning');
        Route::post('/paid-to-delivery-boy', 'paid_to_delivery_boy')->name('paid-to-delivery-boy');
        Route::get('/delivery-boys-payment-histories', 'delivery_boys_payment_histories')->name('delivery-boys-payment-histories');
        Route::get('/delivery-boys-collection-histories', 'delivery_boys_collection_histories')->name('delivery-boys-collection-histories');
        Route::get('/delivery-boy/cancel-request', 'cancel_request_list')->name('delivery-boy.cancel-request');
        Route::post('/delivery-boy/update_status', 'update_status')->name('delivery-boy.update_status');
        
    });
});

Route::group(['middleware' => ['auth', 'prevent-back-history']], function() {
    Route::get('/delivery-boy/pending', function () {
        if (auth()->user() && auth()->user()->user_type == 'delivery_boy') {
            // If approved, redirect to dashboard
            if (auth()->user()->delivery_boy && auth()->user()->delivery_boy->status == 1) {
                return redirect()->route('delivery_boy.dashboard');
            }
            return view('delivery_boy.pending');
        }
        return redirect()->route('home');
    })->name('delivery_boy.pending');
});

Route::group(['middleware' => ['user', 'verified', 'unbanned', 'prevent-back-history']], function() {
    Route::get('/delivery-boy/dashboard', [\App\Http\Controllers\DeliveryBoy\DeliveryBoyController::class, 'index'])->name('delivery_boy.dashboard');
    Route::controller(\App\Http\Controllers\DeliveryBoy\DeliveryBoyController::class)->group(function () {
        Route::get('/assigned-deliveries', 'assigned_delivery')->name('assigned-deliveries');
        Route::get('/pickup-deliveries', 'pickup_delivery')->name('pickup-deliveries');
        Route::get('/on-the-way-deliveries', 'on_the_way_deliveries')->name('on-the-way-deliveries');
        Route::get('/completed-deliveries', 'completed_delivery')->name('completed-deliveries');
        Route::get('/pending-deliveries', 'pending_delivery')->name('pending-deliveries');
        Route::get('/cancelled-deliveries', 'cancelled_delivery')->name('cancelled-deliveries');
        Route::get('/total-collections', 'total_collection')->name('total-collection');
        Route::get('/total-earnings', 'total_earning')->name('total-earnings');
        Route::get('/cancel-request/{id}', 'cancel_request')->name('cancel-request');
        Route::get('/cancel-request-list', 'delivery_boys_cancel_request_list')->name('cancel-request-list');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::post('/orders/update_delivery_status', 'update_delivery_status')->name('delivery-boy.orders.update_delivery_status');
    });

    Route::controller(\App\Http\Controllers\DeliveryBoy\DeliveryBoyController::class)->group(function () {
        Route::get('/delivery-boy/order-detail/{id}', 'order_detail')->name('delivery-boy.order-detail');
        Route::get('/delivery-boy/wallet', 'wallet')->name('delivery-boy.wallet');
        Route::get('/delivery-boy/profile', 'profile')->name('delivery-boy.profile');
        Route::post('/delivery-boy/profile/update', 'profile_update')->name('delivery-boy.profile.update');
    });
    
});

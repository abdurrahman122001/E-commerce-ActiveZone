<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

try {
    if (!Schema::hasTable('vendors')) {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('franchise_id')->nullable();
            $table->unsignedBigInteger('sub_franchise_id')->nullable();
            $table->string('status')->default('pending');
            $table->double('commission_percentage', 8, 2)->default(0);
            $table->double('balance', 15, 2)->default(0);
            $table->timestamps();
        });
        echo "Vendors Table Created\n";
    } else {
        echo "Vendors Table Exists\n";
    }

    if (!Schema::hasColumn('orders', 'vendor_id')) {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_id')->nullable()->after('seller_id');
        });
        echo "Orders Column Added\n";
    } else {
        echo "Orders Column Exists\n";
    }

    if (!Schema::hasTable('vendor_commission_histories')) {
        Schema::create('vendor_commission_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('order_detail_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('franchise_id')->nullable();
            $table->unsignedBigInteger('sub_franchise_id')->nullable();
            $table->double('commission_amount', 15, 2)->default(0);
            $table->timestamps();
        });
        echo "Commission History Table Created\n";
    } else {
        echo "Commission History Table Exists\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

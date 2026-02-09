<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('franchise_id')->nullable();
            $table->unsignedBigInteger('sub_franchise_id')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->double('commission_percentage', 8, 2)->default(0);
            $table->double('balance', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_id')->nullable()->after('seller_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('vendor_id');
        });
    }
};

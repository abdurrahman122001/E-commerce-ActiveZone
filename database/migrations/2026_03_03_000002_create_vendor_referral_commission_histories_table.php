<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_referral_commission_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referrer_vendor_id');   // vendor who referred
            $table->unsignedBigInteger('referred_vendor_id');   // new vendor who joined via referral
            $table->unsignedBigInteger('franchise_package_id')->nullable(); // the package that triggered commission
            $table->string('commission_type')->default('percentage'); // 'percentage' or 'flat'
            $table->double('commission_value', 8, 2)->default(0); // the configured setting value
            $table->double('amount', 20, 2)->default(0);          // actual earned amount
            $table->string('payout_status')->default('pending');   // pending / paid
            $table->timestamps();

            $table->foreign('referrer_vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('referred_vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_referral_commission_histories');
    }
};

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
        Schema::create('state_franchises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('state_id');
            $table->string('franchise_name')->nullable();
            $table->string('referral_code')->unique();
            $table->text('business_experience')->nullable();
            $table->string('id_proof')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->double('balance', 15, 2)->default(0);
            $table->unsignedBigInteger('franchise_package_id')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_acc_name')->nullable();
            $table->string('bank_acc_no')->nullable();
            $table->string('bank_routing_no')->nullable();
            $table->timestamp('invalid_at')->nullable();
            $table->double('commission_percentage', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('state_franchises');
    }
};

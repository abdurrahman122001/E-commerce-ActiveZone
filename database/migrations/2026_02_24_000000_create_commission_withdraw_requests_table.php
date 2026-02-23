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
        Schema::create('commission_withdraw_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID from their respective model
            $table->string('user_type'); // franchise, sub_franchise, vendor, employee
            $table->double('amount', 20, 2);
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->string('bank_name')->nullable();
            $table->string('bank_acc_name')->nullable();
            $table->string('bank_acc_no')->nullable();
            $table->string('bank_routing_no')->nullable();
            $table->text('message')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });

        Schema::table('vendor_commission_histories', function (Blueprint $table) {
            $table->string('vendor_payout_status')->default('unpaid');
            $table->string('franchise_payout_status')->default('unpaid');
            $table->string('sub_franchise_payout_status')->default('unpaid');
            $table->string('employee_payout_status')->default('unpaid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_withdraw_requests');
        Schema::table('vendor_commission_histories', function (Blueprint $table) {
            $table->dropColumn(['vendor_payout_status', 'franchise_payout_status', 'sub_franchise_payout_status', 'employee_payout_status']);
        });
    }
};

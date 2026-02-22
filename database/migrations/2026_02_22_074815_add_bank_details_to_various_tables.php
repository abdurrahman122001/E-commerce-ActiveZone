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
        Schema::table('franchises', function (Blueprint $table) {
            $table->string('bank_name')->nullable();
            $table->string('bank_acc_name')->nullable();
            $table->string('bank_acc_no')->nullable();
            $table->string('bank_routing_no')->nullable();
        });

        Schema::table('sub_franchises', function (Blueprint $table) {
            $table->string('bank_name')->nullable();
            $table->string('bank_acc_name')->nullable();
            $table->string('bank_acc_no')->nullable();
            $table->string('bank_routing_no')->nullable();
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->string('bank_name')->nullable();
            $table->string('bank_acc_name')->nullable();
            $table->string('bank_acc_no')->nullable();
            $table->string('bank_routing_no')->nullable();
        });

        Schema::table('franchise_employees', function (Blueprint $table) {
            $table->string('bank_name')->nullable();
            $table->string('bank_acc_name')->nullable();
            $table->string('bank_acc_no')->nullable();
            $table->string('bank_routing_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franchises', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'bank_acc_name', 'bank_acc_no', 'bank_routing_no']);
        });

        Schema::table('sub_franchises', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'bank_acc_name', 'bank_acc_no', 'bank_routing_no']);
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'bank_acc_name', 'bank_acc_no', 'bank_routing_no']);
        });

        Schema::table('franchise_employees', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'bank_acc_name', 'bank_acc_no', 'bank_routing_no']);
        });
    }
};

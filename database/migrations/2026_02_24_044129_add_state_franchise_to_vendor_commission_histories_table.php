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
        Schema::table('vendor_commission_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('state_franchise_id')->nullable()->after('franchise_id');
            $table->double('state_franchise_commission_amount', 15, 2)->default(0)->after('franchise_commission_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_commission_histories', function (Blueprint $table) {
            $table->dropColumn(['state_franchise_id', 'state_franchise_commission_amount']);
        });
    }
};

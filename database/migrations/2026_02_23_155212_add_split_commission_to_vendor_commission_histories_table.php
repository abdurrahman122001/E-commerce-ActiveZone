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
            // Track each party's share separately for sub-franchise scenarios
            $table->double('franchise_commission_amount', 15, 2)->default(0)->after('commission_amount');
            $table->double('sub_franchise_commission_amount', 15, 2)->default(0)->after('franchise_commission_amount');
            $table->double('employee_commission_amount', 15, 2)->default(0)->after('sub_franchise_commission_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_commission_histories', function (Blueprint $table) {
            $table->dropColumn(['franchise_commission_amount', 'sub_franchise_commission_amount', 'employee_commission_amount']);
        });
    }
};

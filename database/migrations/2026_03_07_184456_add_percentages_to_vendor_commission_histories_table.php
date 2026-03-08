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
            $table->double('franchise_commission_percentage', 8, 2)->default(0)->after('franchise_commission_amount');
            $table->double('sub_franchise_commission_percentage', 8, 2)->default(0)->after('sub_franchise_commission_amount');
            $table->double('state_franchise_commission_percentage', 8, 2)->default(0)->after('state_franchise_commission_amount');
            $table->double('employee_commission_percentage', 8, 2)->default(0)->after('employee_commission_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_commission_histories', function (Blueprint $table) {
            $table->dropColumn([
                'franchise_commission_percentage',
                'sub_franchise_commission_percentage',
                'state_franchise_commission_percentage',
                'employee_commission_percentage'
            ]);
        });
    }
};

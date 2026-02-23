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
        if (!Schema::hasColumn('delivery_boys', 'lat')) {
            Schema::table('delivery_boys', function (Blueprint $table) {
                $table->double('lat', 10, 8)->nullable();
                $table->double('long', 11, 8)->nullable();
            });
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->double('delivery_charge', 20, 2)->default(0)->after('grand_total');
            $table->double('distance', 10, 2)->default(0)->after('delivery_charge');
            $table->double('rider_earning', 20, 2)->default(0)->after('distance');
            $table->double('franchise_earning', 20, 2)->default(0)->after('rider_earning');
            $table->double('company_earning', 20, 2)->default(0)->after('franchise_earning');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_boys', function (Blueprint $table) {
            $table->dropColumn(['lat', 'long']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_charge', 'distance', 'rider_earning', 'franchise_earning', 'company_earning']);
        });
    }
};

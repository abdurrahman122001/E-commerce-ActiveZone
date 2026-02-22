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
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('shop_name')->nullable()->after('added_by_employee_id');
            $table->text('address')->nullable()->after('shop_name');
            $table->integer('city_id')->nullable()->after('address');
            $table->integer('state_id')->nullable()->after('city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['shop_name', 'address', 'city_id', 'state_id']);
        });
    }
};

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
        Schema::table('franchise_packages', function (Blueprint $table) {
            // 'franchise' = City Franchise, 'state_franchise', 'sub_franchise'
            $table->string('package_type')->default('franchise')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franchise_packages', function (Blueprint $table) {
            $table->dropColumn('package_type');
        });
    }
};

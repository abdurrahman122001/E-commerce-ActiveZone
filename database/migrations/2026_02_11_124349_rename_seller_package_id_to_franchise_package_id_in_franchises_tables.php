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
            $table->renameColumn('seller_package_id', 'franchise_package_id');
        });
        Schema::table('sub_franchises', function (Blueprint $table) {
            $table->renameColumn('seller_package_id', 'franchise_package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franchises', function (Blueprint $table) {
            $table->renameColumn('franchise_package_id', 'seller_package_id');
        });
        Schema::table('sub_franchises', function (Blueprint $table) {
            $table->renameColumn('franchise_package_id', 'seller_package_id');
        });
    }
};

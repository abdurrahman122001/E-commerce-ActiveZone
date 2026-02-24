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
        Schema::table('package_commission_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('state_franchise_id')->nullable()->after('id');
            $table->unsignedBigInteger('franchise_id')->nullable()->change();
            $table->unsignedBigInteger('sub_franchise_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_commission_histories', function (Blueprint $table) {
            $table->dropColumn('state_franchise_id');
            $table->unsignedBigInteger('franchise_id')->nullable(false)->change();
            $table->unsignedBigInteger('sub_franchise_id')->nullable(false)->change();
        });
    }
};

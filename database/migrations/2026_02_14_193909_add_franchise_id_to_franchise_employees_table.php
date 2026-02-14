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
        Schema::table('franchise_employees', function (Blueprint $table) {
            $table->unsignedBigInteger('franchise_id')->nullable()->after('sub_franchise_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franchise_employees', function (Blueprint $table) {
            $table->dropColumn('franchise_id');
        });
    }
};

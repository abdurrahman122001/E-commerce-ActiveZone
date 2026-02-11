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
            $table->dropColumn('investment_capacity');
        });

        Schema::table('sub_franchises', function (Blueprint $table) {
            $table->dropColumn('investment_capacity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franchises', function (Blueprint $table) {
            $table->double('investment_capacity', 15, 2)->default(0);
        });

        Schema::table('sub_franchises', function (Blueprint $table) {
            $table->double('investment_capacity', 15, 2)->default(0);
        });
    }
};

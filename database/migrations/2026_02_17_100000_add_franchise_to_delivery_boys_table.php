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
        Schema::table('delivery_boys', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_boys', 'franchise_id')) {
                $table->integer('franchise_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('delivery_boys', 'sub_franchise_id')) {
                $table->integer('sub_franchise_id')->nullable()->after('franchise_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_boys', function (Blueprint $table) {
            $table->dropColumn(['franchise_id', 'sub_franchise_id']);
        });
    }
};

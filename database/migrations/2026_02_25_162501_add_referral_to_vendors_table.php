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
            if (!Schema::hasColumn('vendors', 'referral_code')) {
                $table->string('referral_code')->nullable()->unique()->after('user_id');
            }
            if (!Schema::hasColumn('vendors', 'referred_by_id')) {
                $table->unsignedBigInteger('referred_by_id')->nullable()->after('referral_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['referral_code', 'referred_by_id']);
        });
    }
};

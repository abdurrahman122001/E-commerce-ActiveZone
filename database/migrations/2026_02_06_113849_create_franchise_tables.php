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
        Schema::create('franchises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('franchise_name')->nullable();
            $table->string('referral_code')->unique();
            $table->double('investment_capacity', 15, 2)->default(0);
            $table->text('business_experience')->nullable();
            $table->string('id_proof')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->double('balance', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('sub_franchises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('franchise_id')->nullable(); // Referred/Linked to a parent Franchise
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->string('referral_code')->unique();
            $table->double('investment_capacity', 15, 2)->default(0);
            $table->text('business_experience')->nullable();
            $table->string('id_proof')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->double('balance', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'referred_by')) {
                $table->integer('referred_by')->after('id')->nullable();
            }
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code')->after('referred_by')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchises');
        Schema::dropIfExists('sub_franchises');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['referred_by', 'referral_code']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('state_franchises', function (Blueprint $table) {
            $table->string('offline_payment_id')->nullable()->after('offline_package_payment_proof');
        });

        Schema::table('franchises', function (Blueprint $table) {
            $table->string('offline_payment_id')->nullable()->after('offline_package_payment_proof');
        });

        Schema::table('sub_franchises', function (Blueprint $table) {
            $table->string('offline_payment_id')->nullable()->after('offline_package_payment_proof');
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->string('offline_payment_id')->nullable()->after('franchise_package_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('state_franchises', function (Blueprint $table) {
            $table->dropColumn('offline_payment_id');
        });

        Schema::table('franchises', function (Blueprint $table) {
            $table->dropColumn('offline_payment_id');
        });

        Schema::table('sub_franchises', function (Blueprint $table) {
            $table->dropColumn('offline_payment_id');
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('offline_payment_id');
        });
    }
};

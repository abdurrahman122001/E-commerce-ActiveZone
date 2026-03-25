<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPincodeToFranchiseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('state_franchises', function (Blueprint $table) {
            $table->string('pincode')->nullable()->after('state_id');
        });

        Schema::table('franchises', function (Blueprint $table) {
            $table->string('pincode')->nullable()->after('city_id');
        });

        Schema::table('sub_franchises', function (Blueprint $table) {
            $table->string('pincode')->nullable()->after('area_id');
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
            $table->dropColumn('pincode');
        });

        Schema::table('franchises', function (Blueprint $table) {
            $table->dropColumn('pincode');
        });

        Schema::table('sub_franchises', function (Blueprint $table) {
            $table->dropColumn('pincode');
        });
    }
}

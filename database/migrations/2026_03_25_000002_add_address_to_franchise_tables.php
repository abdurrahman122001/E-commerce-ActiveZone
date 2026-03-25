<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressToFranchiseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('state_franchises', function (Blueprint $table) {
            $table->text('address')->nullable()->after('pincode');
        });

        Schema::table('franchises', function (Blueprint $table) {
            $table->text('address')->nullable()->after('pincode');
        });

        Schema::table('sub_franchises', function (Blueprint $table) {
            $table->text('address')->nullable()->after('pincode');
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
            $table->dropColumn('address');
        });

        Schema::table('franchises', function (Blueprint $table) {
            $table->dropColumn('address');
        });

        Schema::table('sub_franchises', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }
}

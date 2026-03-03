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
        Schema::table('seller_withdraw_requests', function (Blueprint $table) {
            $table->string('withdraw_type')->default('standard')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seller_withdraw_requests', function (Blueprint $table) {
            $table->dropColumn('withdraw_type');
        });
    }
};

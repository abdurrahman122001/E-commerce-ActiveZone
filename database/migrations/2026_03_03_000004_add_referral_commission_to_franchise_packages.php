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
        Schema::table('franchise_packages', function (Blueprint $table) {
            $table->double('referral_commission', 20, 2)->default(0)->after('price');
            $table->string('referral_commission_type', 20)->default('percentage')->after('referral_commission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_packages', function (Blueprint $table) {
            $table->dropColumn(['referral_commission', 'referral_commission_type']);
        });
    }
};

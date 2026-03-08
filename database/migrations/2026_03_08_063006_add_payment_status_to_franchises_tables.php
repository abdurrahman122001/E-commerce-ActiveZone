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
        $tables = ['franchises', 'state_franchises', 'sub_franchises'];
        foreach ($tables as $table_name) {
            Schema::table($table_name, function (Blueprint $table) {
                $table->string('package_payment_status', 20)->default('unpaid')->after('franchise_package_id');
                $table->string('offline_package_payment_proof', 255)->nullable()->after('package_payment_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['franchises', 'state_franchises', 'sub_franchises'];
        foreach ($tables as $table_name) {
            Schema::table($table_name, function (Blueprint $table) {
                $table->dropColumn(['package_payment_status', 'offline_package_payment_proof']);
            });
        }
    }
};

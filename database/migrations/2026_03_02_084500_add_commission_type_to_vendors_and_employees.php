<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionTypeToVendorsAndEmployees extends Migration
{
    public function up()
    {
        $tables = ['vendors', 'franchise_employees'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'commission_type')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->string('commission_type')->default('percentage')->after('commission_percentage');
                });
            }
        }
    }

    public function down()
    {
        $tables = ['vendors', 'franchise_employees'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'commission_type')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropColumn('commission_type');
                });
            }
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryBoysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('delivery_boys')) {
            Schema::create('delivery_boys', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('franchise_id')->nullable();
                $table->integer('sub_franchise_id')->nullable();
                $table->double('total_earning', 20, 2)->default(0);
                $table->double('cash_collected', 20, 2)->default(0);
                $table->integer('status')->default(0); // 0: pending, 1: approved
                $table->timestamps();
            });
        } else {
            Schema::table('delivery_boys', function (Blueprint $table) {
                if (!Schema::hasColumn('delivery_boys', 'franchise_id')) {
                    $table->integer('franchise_id')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('delivery_boys', 'sub_franchise_id')) {
                    $table->integer('sub_franchise_id')->nullable()->after('franchise_id');
                }
                if (!Schema::hasColumn('delivery_boys', 'status')) {
                    $table->integer('status')->default(0)->after('cash_collected');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('delivery_boys');
    }
}

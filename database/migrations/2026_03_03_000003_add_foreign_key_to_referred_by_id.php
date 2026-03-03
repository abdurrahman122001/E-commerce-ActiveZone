<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->index('referred_by_id');
            // Adding foreign key to referred_by_id pointing to vendors.id
            $table->foreign('referred_by_id')->references('id')->on('vendors')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropForeign(['referred_by_id']);
            $table->dropIndex(['referred_by_id']);
        });
    }
};

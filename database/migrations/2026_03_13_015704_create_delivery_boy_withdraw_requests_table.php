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
        Schema::create('delivery_boy_withdraw_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->double('amount', 20, 2);
            $table->longText('message')->nullable();
            $table->integer('status')->default(0);
            $table->integer('viewed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_boy_withdraw_requests');
    }
};

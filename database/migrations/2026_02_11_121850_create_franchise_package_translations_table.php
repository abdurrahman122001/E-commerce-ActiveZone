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
        Schema::create('franchise_package_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('franchise_package_id');
            $table->string('name')->nullable();
            $table->string('lang')->nullable();
            $table->timestamps();

            $table->foreign('franchise_package_id')->references('id')->on('franchise_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_package_translations');
    }
};

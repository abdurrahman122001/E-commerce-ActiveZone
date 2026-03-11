<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Shops (seller / vendor verification)
        Schema::table('shops', function (Blueprint $table) {
            $table->text('additional_doc_request')->nullable()->after('verification_info');
            $table->text('additional_doc_request_note')->nullable()->after('additional_doc_request');
        });

        // Franchises (city level)
        if (Schema::hasTable('franchises')) {
            Schema::table('franchises', function (Blueprint $table) {
                $table->text('additional_doc_request')->nullable();
                $table->text('additional_doc_request_note')->nullable();
            });
        }

        // Sub-Franchises
        if (Schema::hasTable('sub_franchises')) {
            Schema::table('sub_franchises', function (Blueprint $table) {
                $table->text('additional_doc_request')->nullable();
                $table->text('additional_doc_request_note')->nullable();
            });
        }

        // State Franchises
        if (Schema::hasTable('state_franchises')) {
            Schema::table('state_franchises', function (Blueprint $table) {
                $table->text('additional_doc_request')->nullable();
                $table->text('additional_doc_request_note')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['additional_doc_request', 'additional_doc_request_note']);
        });
        if (Schema::hasTable('franchises')) {
            Schema::table('franchises', function (Blueprint $table) {
                $table->dropColumn(['additional_doc_request', 'additional_doc_request_note']);
            });
        }
        if (Schema::hasTable('sub_franchises')) {
            Schema::table('sub_franchises', function (Blueprint $table) {
                $table->dropColumn(['additional_doc_request', 'additional_doc_request_note']);
            });
        }
        if (Schema::hasTable('state_franchises')) {
            Schema::table('state_franchises', function (Blueprint $table) {
                $table->dropColumn(['additional_doc_request', 'additional_doc_request_note']);
            });
        }
    }
};

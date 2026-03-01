<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_commission_histories', function (Blueprint $table) {
            // null = beneficiary is franchise/state_franchise from another entity's package purchase
            // vendor_id tracks which vendor's package triggered this commission
            $table->unsignedBigInteger('vendor_id')->nullable()->after('state_franchise_id');
            // 'type' identifies what relationship this commission entry represents:
            // 'sub_to_city'   = sub-franchise package → city franchise earns
            // 'sub_to_state'  = sub-franchise package → state franchise earns
            // 'city_to_state' = city franchise package → state franchise earns
            // 'vendor_package'= vendor package → franchise chain earns
            $table->string('type')->nullable()->after('vendor_id');
            // who is the beneficiary (earner)
            $table->string('beneficiary_type')->nullable()->after('type'); // 'franchise', 'sub_franchise', 'state_franchise'
        });
    }

    public function down(): void
    {
        Schema::table('package_commission_histories', function (Blueprint $table) {
            $table->dropColumn(['vendor_id', 'type', 'beneficiary_type']);
        });
    }
};

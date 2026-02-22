<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasColumn('franchise_employees', 'balance')) {
        Schema::table('franchise_employees', function (Blueprint $table) {
            $table->double('balance', 20, 2)->default(0)->after('commission_percentage');
        });
        echo "SUCCESS\n";
    } else {
        echo "ALREADY EXISTS\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SubFranchise;
use App\Models\Franchise;

$sf = SubFranchise::find(1);
if ($sf) {
    echo "Sub-Franchise ID: 1\n";
    echo "  Franchise ID: " . ($sf->franchise_id ?? 'NULL') . "\n";
    echo "  Commission %: " . $sf->commission_percentage . "\n";
    echo "  Balance: " . $sf->balance . "\n";
    
    if ($sf->franchise_id) {
        $f = Franchise::find($sf->franchise_id);
        if ($f) {
            echo "Franchise ID: " . $f->id . "\n";
            echo "  Commission %: " . $f->commission_percentage . "\n";
            echo "  Balance: " . $f->balance . "\n";
        } else {
            echo "Parent Franchise ID " . $sf->franchise_id . " NOT  found in franchises table\n";
        }
    } else {
        echo "Sub-Franchise 1 has NO parent franchise assigned.\n";
    }
} else {
    echo "Sub-Franchise 1 not found\n";
}

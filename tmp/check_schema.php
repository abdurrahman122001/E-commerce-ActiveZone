<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$tables = ['state_franchises', 'franchises', 'sub_franchises'];
foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "Columns for $table: " . implode(', ', Schema::getColumnListing($table)) . "\n";
    } else {
        echo "$table does not exist.\n";
    }
}

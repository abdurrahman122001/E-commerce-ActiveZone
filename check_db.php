<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tableName = 'delivery_boys';
if (Schema::hasTable($tableName)) {
    $columns = Schema::getColumnListing($tableName);
    echo "Table '$tableName' exists. Columns: " . implode(', ', $columns) . "\n";
} else {
    echo "Table '$tableName' does NOT exist.\n";
}

$userTypes = DB::table('users')->select('user_type')->distinct()->get();
echo "User types in users table: ";
foreach($userTypes as $type) echo $type->user_type . ", ";
echo "\n";

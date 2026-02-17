<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
$tables = DB::select('SHOW TABLES');
file_put_contents('temp_tables.txt', print_r($tables, true));

<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\Schema;
$columns = Schema::getColumnListing('delivery_boys');
file_put_contents('temp_schema.php', json_encode($columns));

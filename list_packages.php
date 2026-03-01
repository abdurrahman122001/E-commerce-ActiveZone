<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$packages = App\Models\FranchisePackage::all();
foreach($packages as $p) {
    echo $p->id . ': ' . $p->package_type . ' - ' . $p->name . "\n";
}

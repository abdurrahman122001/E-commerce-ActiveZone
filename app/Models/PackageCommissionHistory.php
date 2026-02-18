<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageCommissionHistory extends Model
{
    protected $fillable = [
        'franchise_id',
        'sub_franchise_id',
        'franchise_package_id',
        'amount',
        'percentage'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageCommissionHistory extends Model
{
    protected $fillable = [
        'state_franchise_id',
        'franchise_id',
        'sub_franchise_id',
        'vendor_id',
        'franchise_package_id',
        'amount',
        'percentage',
        'type',
        'beneficiary_type',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function sub_franchise()
    {
        return $this->belongsTo(SubFranchise::class);
    }

    public function state_franchise()
    {
        return $this->belongsTo(StateFranchise::class);
    }

    public function franchise_package()
    {
        return $this->belongsTo(FranchisePackage::class);
    }
}

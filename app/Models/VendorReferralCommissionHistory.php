<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorReferralCommissionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_vendor_id',
        'referred_vendor_id',
        'franchise_package_id',
        'commission_type',
        'commission_value',
        'amount',
        'payout_status',
    ];

    public function referrer()
    {
        return $this->belongsTo(Vendor::class, 'referrer_vendor_id');
    }

    public function referred()
    {
        return $this->belongsTo(Vendor::class, 'referred_vendor_id');
    }

    public function referredVendor()
    {
        return $this->belongsTo(Vendor::class, 'referred_vendor_id');
    }

    public function franchise_package()
    {
        return $this->belongsTo(FranchisePackage::class);
    }

    public function package()
    {
        return $this->belongsTo(FranchisePackage::class, 'franchise_package_id');
    }
}

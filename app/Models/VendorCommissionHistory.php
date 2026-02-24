<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorCommissionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_detail_id',
        'vendor_id',
        'franchise_id',
        'sub_franchise_id',
        'state_franchise_id',
        'commission_amount',
        'franchise_commission_amount',
        'sub_franchise_commission_amount',
        'state_franchise_commission_amount',
        'employee_commission_amount',
        'franchise_payout_status',
        'sub_franchise_payout_status',
        'state_franchise_payout_status',
        'vendor_payout_status',
        'employee_payout_status',
    ];

    public function state_franchise()
    {
        return $this->belongsTo(StateFranchise::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function order_detail()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function sub_franchise()
    {
        return $this->belongsTo(SubFranchise::class);
    }
}

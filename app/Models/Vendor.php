<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($vendor) {
            if (empty($vendor->referral_code)) {
                $vendor->referral_code = static::generateUniqueReferralCode();
            }
        });
    }

    public static function generateUniqueReferralCode()
    {
        do {
            $code = 'VND' . strtoupper(\Str::random(8));
        } while (static::where('referral_code', $code)->exists());
        return $code;
    }

    protected $fillable = [
        'user_id',
        'franchise_id',
        'sub_franchise_id',
        'state_franchise_id',
        'status',
        'commission_percentage',
        'added_by_employee_id',
        'bank_name',
        'bank_acc_name',
        'bank_acc_no',
        'bank_routing_no',
        'shop_name',
        'address',
        'city_id',
        'state_id',
        'area_id',
        'lat',
        'long',
        'referral_code',
        'referred_by_id'
    ];

    public function addedByEmployee()
    {
        return $this->belongsTo(FranchiseEmployee::class, 'added_by_employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function referrer()
    {
        return $this->belongsTo(Vendor::class, 'referred_by_id');
    }

    public function referrals()
    {
        return $this->hasMany(Vendor::class, 'referred_by_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}

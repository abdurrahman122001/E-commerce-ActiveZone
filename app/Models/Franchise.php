<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Franchise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'state_id',
        'city_id',
        'franchise_name',
        'referral_code',
        'business_experience',
        'id_proof',
        'status',
        'balance',
        'franchise_package_id',
        'pan_number',
        'commission_percentage',
        'bank_name',
        'bank_acc_name',
        'bank_acc_no',
        'bank_routing_no'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function sub_franchises()
    {
        return $this->hasMany(SubFranchise::class);
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }

    public function franchise_package()
    {
        return $this->belongsTo(FranchisePackage::class);
    }
}

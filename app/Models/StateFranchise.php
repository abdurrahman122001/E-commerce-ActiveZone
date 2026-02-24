<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateFranchise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'state_id',
        'franchise_name',
        'referral_code',
        'business_experience',
        'id_proof',
        'status',
        'balance',
        'franchise_package_id',
        'pan_number',
        'bank_name',
        'bank_acc_name',
        'bank_acc_no',
        'bank_routing_no',
        'invalid_at',
        'commission_percentage'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function franchises()
    {
        return $this->hasMany(Franchise::class, 'state_franchise_id');
    }

    public function sub_franchises()
    {
        return $this->hasMany(SubFranchise::class, 'state_franchise_id');
    }

    public function franchise_package()
    {
        return $this->belongsTo(FranchisePackage::class);
    }
}

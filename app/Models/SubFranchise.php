<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubFranchise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'state_id',
        'franchise_id',
        'city_id',
        'area_id',
        'referral_code',
        'business_experience',
        'id_proof',
        'status',
        'balance',
        'franchise_package_id',
        'pan_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
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

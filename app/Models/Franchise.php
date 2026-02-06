<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Franchise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city_id',
        'franchise_name',
        'referral_code',
        'investment_capacity',
        'business_experience',
        'id_proof',
        'status',
        'balance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function sub_franchises()
    {
        return $this->hasMany(SubFranchise::class);
    }
}

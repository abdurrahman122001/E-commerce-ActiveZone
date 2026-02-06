<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubFranchise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'franchise_id',
        'city_id',
        'area_id',
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

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'franchise_id',
        'sub_franchise_id',
        'status',
        'commission_percentage',
    ];

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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class FranchiseEmployee extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'role',
        'franchise_level',
        'city_id',
        'sub_franchise_id',
        'franchise_id',
        'is_active',
        'status',
        'created_by',
        'commission_percentage',
        'bank_name',
        'bank_acc_name',
        'bank_acc_no',
        'bank_routing_no'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function subFranchise()
    {
        return $this->belongsTo(SubFranchise::class, 'sub_franchise_id');
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id');
    }
}

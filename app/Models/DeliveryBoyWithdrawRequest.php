<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryBoyWithdrawRequest extends Model
{
    use HasFactory;
    
    protected $table = 'delivery_boy_withdraw_requests';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deliveryBoy()
    {
        return $this->hasOne(DeliveryBoy::class, 'user_id', 'user_id');
    }

    // Virtual attribute to unify with CommissionWithdrawRequest for admin view
    public function getUserTypeAttribute()
    {
        return 'delivery_boy';
    }

    // Normalize status to string for unified display
    public function getStatusStringAttribute()
    {
        if ($this->status == 1) return 'approved';
        if ($this->status == 2) return 'rejected';
        return 'pending';
    }
}

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
}

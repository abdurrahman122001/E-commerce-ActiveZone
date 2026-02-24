<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionWithdrawRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'amount',
        'status',
        'bank_name',
        'bank_acc_name',
        'bank_acc_no',
        'bank_routing_no',
        'message',
        'admin_note',
    ];

    public function getRequesterAttribute()
    {
        if ($this->user_type == 'franchise') {
            return Franchise::find($this->user_id);
        } elseif ($this->user_type == 'sub_franchise') {
            return SubFranchise::find($this->user_id);
        } elseif ($this->user_type == 'vendor') {
            return Vendor::find($this->user_id);
        } elseif ($this->user_type == 'state_franchise') {
            return StateFranchise::find($this->user_id);
        } elseif ($this->user_type == 'employee') {
            return FranchiseEmployee::find($this->user_id);
        }
        return null;
    }
}

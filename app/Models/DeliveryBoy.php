<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class DeliveryBoy extends Model
{
    protected $fillable = [
        'user_id',
        'franchise_id',
        'sub_franchise_id',
        'total_earning',
        'cash_collected',
        'status',
        'online_status',
        'vehicle_details',
        'id_proof',
        'driving_license',
        'bank_name',
        'bank_account_no',
        'bank_routing_no',
        'holder_name',
        'lat',
        'long',
        'location'
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }
}

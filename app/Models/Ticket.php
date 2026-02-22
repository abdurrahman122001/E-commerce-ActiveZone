<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class Ticket extends Model
{
    use PreventDemoModeChanges;

    protected $fillable = [
        'code', 'user_id', 'user_role', 'subject', 'details', 'files', 'status', 'client_viewed', 'viewed',
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function ticketreplies()
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at', 'desc');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'amount',
        'type',
        'payment_method',
        'remark'
    ];

    public function employee()
    {
        return $this->belongsTo(FranchiseEmployee::class, 'employee_id');
    }
}

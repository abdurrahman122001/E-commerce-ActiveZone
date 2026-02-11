<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FranchisePackageTranslation extends Model
{
    protected $guarded = [];

    public function franchise_package()
    {
        return $this->belongsTo(FranchisePackage::class);
    }
}

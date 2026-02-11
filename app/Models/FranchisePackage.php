<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use App;

class FranchisePackage extends Model
{
    use PreventDemoModeChanges;

    protected $guarded = [];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $translation = $this->hasMany(FranchisePackageTranslation::class)->where('lang', $lang)->first();
        return $translation != null ? $translation->$field : $this->$field;
    }

    public function franchise_package_translations()
    {
        return $this->hasMany(FranchisePackageTranslation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

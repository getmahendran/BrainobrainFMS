<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
    protected $fillable = ['name'];

    public function fee()
    {
        return $this->hasMany('App\Fee');
    }

    public function fee_collection()
    {
        return $this->hasMany('App\FeeCollection');
    }
}

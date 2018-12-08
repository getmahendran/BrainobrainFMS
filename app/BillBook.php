<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillBook extends Model
{
    protected $fillable     =   ['franchisee_id','fee_type_id','from','till','wasted_count','comments','status'];

    public function fee_type()
    {
        return $this->belongsTo('App\FeeType');
    }
}

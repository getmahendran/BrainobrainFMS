<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable =   ['batch_name','franchisee_id','faculty_account_id','start_date','end_date'];

    protected function level()
    {
        return $this->hasMany('App\Level');
    }

    protected function franchisee()
    {
        return $this->belongsTo('App\Franchisee');
    }


}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseBranch extends Model
{
    public function course()
    {
        return $this->belongsTo('App\Course','branch_course_id','id')->where('status','=',1);
    }
}
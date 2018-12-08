<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['program_name','status'];

    public function course()
    {
        return $this->hasMany('App\Course')->orderBy("sequence_number");
    }

    public function activeCourses()
    {
        return $this->hasMany('App\Course')->orderBy("sequence_number")->where("status","=",1);
    }
    public function getPrimaryCourse()
    {

    }
}

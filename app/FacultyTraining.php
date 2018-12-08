<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacultyTraining extends Model
{
    protected $fillable     =   ['course_id','faculty_id'];

    public function course()
    {
        return $this->belongsTo('App\Course');
    }
    public function faculty()
    {
        return $this->belongsTo('App\Faculty');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['program_id', 'course_name', 'duration', 'status', 'sequence_number'];

    public function getPrimaryCourse($program_id){
        $courses= Course::where('program_id',$program_id)->get();
        if(count($courses))
            return $courses;
        else
            return 0;
    }

    public function level()
    {
        return $this->hasMany('App\Level');
    }

    public function program()
    {
        return $this->belongsTo('App\Program');
    }

    public function course_branches()
    {
        return $this->hasMany('App\CourseBranch');
    }
}

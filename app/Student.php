<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name','dob','gender','path','school_name','standard','father_name','father_occupation','office_address','monthly_income','mother_name','mother_occupation','residence_address','father_mobile','mother_mobile','father_email','mother_email','sibling_1_name','sibling_1_dob','sibling_2_name','sibling_2_dob','source','comments','level_id','status','graduated','about_child'];

    public function level()
    {
        return $this->hasMany('App\Level');
    }

    public function getPathAttribute($value)
    {
        return "/images/".$value;
    }
}
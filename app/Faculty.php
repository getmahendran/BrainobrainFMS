<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = [
        'name', 'user_name','gender', 'path', 'dob','father_name','permanent_address','contact_no1','contact_no2', 'occupation','qualification','email','married','spouse_name','spouse_qualification','spouse_occupation','spouse_dob','wedding_anniversary','no_of_children','child1_name','child1_dob','child2_name','child2_dob','child3_name','child3_dob','family_income','hobbies','special_at','past_experience','languages_known','attended_by','reference_no',
    ];

    public function getPathAttribute($value)
    {
        return "/images/".$value;
    }

    public function faculty_training()
    {
        return $this->hasMany("App\FacultyTraining");
    }
}

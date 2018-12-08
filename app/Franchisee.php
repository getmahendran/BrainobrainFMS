<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Franchisee extends Model
{
    protected $fillable = [
        'id','name', 'center_code','gender', 'dob','father_name','permanent_address','contact_no1','contact_no2', 'occupation','qualification','email','married','spouse_name','spouse_dob','spouse_occupation','spouse_qualification','wedding_anniversary','child1_name','child1_dob','child2_name','child2_dob','child3_name','child3_dob','family_income','hobbies','special_at','past_experience','languages_known','path','franchisee_name','franchisee_address','attended_by','branch_name','area_id','user_id','status'
    ];

    public function area()
    {
        return $this->belongsTo('App\Area');
    }

    public function getPathAttribute($value){
        return '/images/'.$value;
    }

    public function getUser($franchisee_id)
    {
        return User::where('account_key', $franchisee_id)->where('acc_type', 2)->first();
    }

    public function batch()
    {
        return $this->hasMany('App\Batch');
    }

    public function questionPaperRequest()
    {
        return $this->hasMany("App\QuestionPaperRequest");
    }

}
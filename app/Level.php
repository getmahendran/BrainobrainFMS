<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['student_id','student_no','course_id','franchisee_id','start_date','end_date','status','transfer_id','marks'];

    public function getNextStudentNumber($franchisee_id){
        $level = Level::where('franchisee_id',$franchisee_id)->orderBy('id','desc')->first();
        if($level){
            $num = substr($level->student_no,-3)+1;
            if($num < 10)
                return 'S'.Franchisee::find($franchisee_id)->center_code.'00'.$num;
            elseif ($num < 100)
                return 'S'.Franchisee::find($franchisee_id)->center_code.'0'.$num;
            else
                return 'S'.Franchisee::find($franchisee_id)->center_code.$num;
        }
        else
            return 'S'.Franchisee::find($franchisee_id)->center_code.'001';
    }

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function feeCollection()
    {
        return $this->hasMany('App\FeeCollection');
    }

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function franchisee()
    {
        return $this->belongsTo('App\Franchisee');
    }

    public function batch()
    {
        return $this->belongsTo('App\Batch');
    }
}

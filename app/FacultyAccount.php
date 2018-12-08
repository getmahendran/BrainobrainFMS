<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacultyAccount extends Model
{
    protected $fillable = [
        'faculty_id','faculty_code','franchisee_id','status',
    ];

    public function getNextFacultyNumber($franchisee_id){
        $faculty = FacultyAccount::where('franchisee_id',$franchisee_id)->orderBy('id','desc')->first();
        if($faculty) {
            $num = substr($faculty->faculty_code, -2) + 1;
            if ($num < 10)
                return 'F' . Franchisee::find($franchisee_id)->center_code . '0' . $num;
            else
                return 'F' . Franchisee::find($franchisee_id)->center_code . $num;
        }
        else
            return 'F'.Franchisee::find($franchisee_id)->center_code.'01';
    }

    public function faculty(){
        return $this->belongsTo('App\Faculty');
    }

    public function franchisee(){
        return $this->belongsTo('App\Franchisee');
    }

    public function getUser($faculty_account_id){

    }
}
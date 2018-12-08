<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable=['area_id','fee_type_id','price','effective_from','status'];

    public static function getCurrentFee($area_id,$fee_type_id){
        $fees = Fee::where('area_id',$area_id)->where('fee_type_id',$fee_type_id)->orderBy('id','desc')->get();
        foreach ($fees as $fee){
            if(date('Y-m-d')>=$fee->effective_from){
                $amount = $fee->amount;
                $fee_id = $fee->id;
                return array(
                    'amount' =>$amount,
                    'fee_id' => $fee_id,
                    );
            }
            else
                continue;
        }
    }

    public function area(){
        return $this->belongsTo('App\Area',"area_id","id");
    }

    public function feeCollection()
    {
        return $this->hasMany('App\FeeCollection');
    }

    public function feetype(){
        return $this->belongsTo('App\FeeType');
    }
}

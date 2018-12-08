<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FeeCollection extends Model
{
    protected $fillable = ['level_id','fee_payment_type','student_id','fee_id','bill_book_id','fee_description','receipt_id','physical_receipt_no','physical_receipt_date','royalty_status','fee_type_id','status','comments'];

    public function level()
    {
        return $this->belongsTo('App\Level');
    }

    public function fee()
    {
        return $this->belongsTo('App\Fee');
    }

    public function fee_type()
    {
        return $this->belongsTo('App\FeeType');
    }

    public function getFee($level_id){
        $fee_coll   =   DB::table('fees as fs')
                        ->whereRaw('status=1 AND area_id in (SELECT area_id FROM franchisees WHERE id=5)')
                        ->get();
        return FeeCollection::hydrate($fee_coll->toArray());
    }

    public function getMonthlyFeePaid($level_id){

    }
}

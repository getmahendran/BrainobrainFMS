<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionPaperRequest extends Model
{
    protected $fillable = ['franchisee_id','user_id','status'];

    public function franchisee()
    {
        return $this->belongsTo("App\Franchisee");
    }

    public function qpRequestDetail()
    {
        return $this->hasMany("App\QuestionPaperRequestDetail");
    }

    public function getIdAttr($value)
    {
        return "QPR".$value;
    }
}

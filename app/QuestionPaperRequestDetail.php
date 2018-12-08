<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionPaperRequestDetail extends Model
{
    protected $fillable = ['question_paper_request_id','level_id'];

    public function qpRequest()
    {
        return $this->belongsTo("App\QuestionPaperRequest");
    }

    public function level()
    {
        return $this->belongsTo("App\Level");
    }
}

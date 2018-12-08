<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Master extends Model
{
    protected $fillable = [
        'name','user_name', 'mobile', 'email',
    ];

}

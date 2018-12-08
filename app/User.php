<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'name', 'password','user_name','acc_type','status'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function master()
    {
        return $this->belongsTo('App\Master','account_key');
    }
    public function franchisee()
    {
        return $this->belongsTo('App\Franchisee','account_key');
    }
}

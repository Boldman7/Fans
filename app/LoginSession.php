<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginSession extends Model
{
    //
    protected $fillable = [
        'id', 'user_id', 'user_name', 'browser', 'os', 'machine_name', 'location', 'ip_address', 'date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}

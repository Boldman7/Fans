<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class UserList extends Model
{
    protected $fillable = [
        'user_id',
        'saved_user_id',
        'list_type_id'
    ];

    public function listType()
    {
        return $this->belongsTo('App\UserListType','like_type_id','id');
    }

    public function savedUsers()
    {
        return $this->belongsTo('App\User','saved_user_id', 'id');
    }
}

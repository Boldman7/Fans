<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class UserListType extends Model
{
    protected $fillable = [
        'user_id',
        'list_type'
    ];

    public function lists()
    {
        return $this->hasMany('App\UserList','list_type_id');
    }
}

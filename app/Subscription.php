<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Subscription extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'subscription_id', 'follower_id', 'leader_id', 'start_at', 'cancel_at'
    ];
}

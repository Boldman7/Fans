<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


//use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    //use SoftDeletes;

    // *
    //  * The attributes that should be mutated to dates.
    //  *
    //  * @var array
     
    // protected $dates = ['deleted_at'];
    
    protected $table = 'media';

    protected $fillable = ['title', 'type', 'source'];

    public function album()
    {
        return $this->hasOne('App\Album', 'id', 'preview_id');
    }

    public function albums()
    {
        return $this->belongsToMany('App\Album', 'album_media', 'media_id', 'album_id');
    }

    public function post()
    {
        return $this->belongsToMany('App\Post', 'post_media', 'media_id', 'post_id');
    }

    public function wallpaper()
    {
        return $this->belongTo('App\Wallpaper');
    }
}

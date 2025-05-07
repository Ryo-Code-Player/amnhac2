<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $table = 'post';
    protected $fillable = ['id', 'description','created_at','updated_at','link',
    'user_id','image','like','dislike','comment','share','status','post_form','post_singer'];

    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function postUser()
    {
        return $this->hasMany(PostUser::class, 'post_id');
    }

    public function commentUser()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

   
}

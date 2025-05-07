<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostUser extends Model
{
    //
    protected $table = 'post_user';
    protected $fillable = ['id', 'post_id', 'user_id','status'];
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
    
}

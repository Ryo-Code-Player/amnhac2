<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = [
        'id',
        'post_id',
        'user_id',
        'content',
        'status',
        'created_at',
        'updated_at',
        'like',
        'reply',
    ];

    protected $appends = ['time'];
    public function getTimeAttribute(){
        return $this->created_at->diffForHumans();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function commentChildrenUser(){
        return $this->hasMany(CommentChildren::class, 'comment_id');
    }

    public function replyComment(){
        return $this->hasMany(ReplyComment::class, 'comment_id');
    }

    

    
    
}

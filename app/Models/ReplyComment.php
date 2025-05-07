<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplyComment extends Model
{
    //
    protected $table = 'reply_comment';
    protected $fillable = [
        'id',
        'comment_id',
        'user_id',
        'content',
        'like',
        'created_at',
        'updated_at',
    ];

    protected $appends = ['time'];
    public function getTimeAttribute(){
        return $this->created_at->diffForHumans();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
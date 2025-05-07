<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentChildren extends Model
{
    //
    protected $table = 'comment_children';
    protected $fillable = [
        'id',
        'comment_id',
        'user_id',
        'content',
        'status',
        'created_at',
        'updated_at',
       
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}

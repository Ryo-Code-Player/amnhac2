<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    //
    protected $table = 'follow';
    protected $fillable = ['user_id', 'user_follow','created_at','updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userFollow()
    {
        return $this->belongsTo(User::class, 'user_follow');
    }
}

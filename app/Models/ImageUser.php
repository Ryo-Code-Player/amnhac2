<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageUser extends Model
{
    //
    protected $table = 'image_user';
    protected $fillable = ['user_id', 'image', 'status', 'created_at', 'updated_at','status'];
}

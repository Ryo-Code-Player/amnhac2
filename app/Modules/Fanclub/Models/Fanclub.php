<?php

namespace App\Modules\Fanclub\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Fanclub extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'photo', 'summary', 'content', 'singer_id', 'status', 'user_id',
    ];

    // public function singer()
    // {
    //     return $this->belongsTo(Singer::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blogs()
    {
        return $this->hasMany(FanclubBlog::class);
    }

    public function items()
    {
        return $this->hasMany(FanclubItem::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'fanclub_users')->withPivot('role_id');
    }

    // public function events()
    // {
    //     return $this->hasMany(Event::class);
    // }
}

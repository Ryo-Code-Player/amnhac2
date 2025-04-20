<?php

namespace App\Modules\Event\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'photo', 'summary', 'description', 'resources', 'timestart', 'timeend', 'diadiem', 'tags', 'event_type_id'];

    public function eventType()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_users')->withPivot('role_id', 'vote');
    }

    public function blogs()
    {
        return $this->hasMany(EventBlog::class);
    }

    // public function attendances()
    // {
    //     return $this->hasMany(EventAttendance::class);
    // }

    public function groups()
    {
        return $this->hasMany(EventGroup::class);
    }
}

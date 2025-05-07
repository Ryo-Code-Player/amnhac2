<?php

namespace App\Modules\Event\Models;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'event_id', 'role_id', 'vote','created_at','updated_at','code'];

    protected $appends = ['created_at_format'];

    public function getCreatedAtFormatAttribute()
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }


    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}

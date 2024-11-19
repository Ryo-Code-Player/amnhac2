<?php

namespace App\Modules\Event\Models;

use App\Modules\Group\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventGroup extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'event_id'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}

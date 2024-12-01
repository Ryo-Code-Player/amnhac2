<?php

namespace App\Modules\Event\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventType extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'status'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}

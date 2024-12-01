<?php

namespace App\Modules\interact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Motion extends Model
{
    protected $fillable = ['title', 'icon'];

    public function motionItems()
    {
        return $this->hasMany(MotionItem::class);
    }
}

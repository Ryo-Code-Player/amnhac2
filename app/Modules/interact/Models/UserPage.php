<?php

namespace App\Modules\interact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPage extends Model
{
    protected $fillable = ['title', 'summary', 'slug'];

    protected $casts = [
        'items' => 'array',
    ];
}

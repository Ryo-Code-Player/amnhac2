<?php

namespace App\Modules\Group\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupType extends Model
{
    use HasFactory;
    protected $fillable = ['nametitle', 'description'];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}

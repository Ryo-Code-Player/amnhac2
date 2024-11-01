<?php

namespace App\Modules\Group\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupResource extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'resource_id', 'resource_code'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}

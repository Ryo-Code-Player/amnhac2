<?php

namespace App\Modules\Group\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['type_id', 'title', 'photo', 'slug', 'description', 'is_private', 'status'];

    public function blogs()
    {
        return $this->belongsToMany(GroupBlog::class, 'group_blogs');
    }

    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function type()
    {
        return $this->belongsTo(GroupType::class, 'type_id');
    }

    public function resources()
    {
        return $this->belongsToMany(GroupResource::class, 'group_resources');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_members');
    }
    
}

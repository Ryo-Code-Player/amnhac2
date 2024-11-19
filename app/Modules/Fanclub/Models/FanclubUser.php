<?php

namespace App\Modules\Fanclub\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;

class FanclubUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'fanclub_id', 'role_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fanclub()
    {
        return $this->belongsTo(Fanclub::class, 'fanclub_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}

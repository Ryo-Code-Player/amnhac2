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
        return $this->belongsTo(User::class);
    }

    public function fanclub()
    {
        return $this->belongsTo(Fanclub::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}

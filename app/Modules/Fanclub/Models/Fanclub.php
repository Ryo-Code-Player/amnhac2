<?php

namespace App\Modules\Fanclub\Models;

use App\Modules\Event\Models\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Fanclub extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'photo', 'summary', 'content', 'singer_id', 'status', 'user_id','quantity'
    ];


    protected $appends = ['check_fanclub'];

    public function getCheckFanclubAttribute(){
        $fanclubUser = FanclubUser::where('fanclub_id',$this->id)->where('user_id',auth()->user()->id)->first();
        return $fanclubUser ? true : false;
    }

    // public function singer()
    // {
    //     return $this->belongsTo(Singer::class);
    // }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blogs()
    {
        return $this->hasMany(FanclubBlog::class);
    }

    public function items()
    {
        return $this->hasMany(FanclubItem::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'fanclub_users')->withPivot('role_id');
    }

    // public function events()
    // {
    //     return $this->hasMany(Event::class);
    // }
}

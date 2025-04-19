<?php

namespace App\Modules\Tuongtac\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\TuongTac\Models\TMotionItem;
use App\Models\User;
use Dom\Text;

class TComment extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'item_code', 'user_id', 'content', 'parent_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(TComment::class, 'parent_id');
    }

    public function Tmotion()
    {
        return $this->hasOne(TMotionItem::class, 'item_id')
            ->where('item_code', 'comment');
    }
}
 
 
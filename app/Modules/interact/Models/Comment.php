<?php

namespace App\Modules\interact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Modules\item\Models\ItemType;

class Comment extends Model
{
    protected $fillable = ['item_id', 'item_code', 'user_id', 'content', 'parent_id', 'comment_resources'];

    protected $casts = [
        'comment_resources' => 'array',
    ];

    public function item()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}

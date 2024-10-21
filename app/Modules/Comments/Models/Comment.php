<?php
namespace App\Modules\Comments\Models;
use App\Models\User; // Đảm bảo đường dẫn đúng


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'item_code',
        'user_id',
        'content',
        'parent_id',
        'comment_resources',
    ];

    // Quan hệ với Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Quan hệ với Comment cha
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Quan hệ với các Comment con (replies)
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    
    
     // Quan hệ với các Comment con
     public function children()
     {
         return $this->hasMany(Comment::class, 'parent_id'); // Sử dụng 'parent_id' để lấy các bình luận con
     }

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // 'user_id' là khóa ngoại trong bảng comments
    }
}


<?php

namespace App\Modules\Blog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Tag\Models\Tag;
use App\Modules\TuongTac\Models\TComment;
use App\Modules\TuongTac\Models\TMotionItem;
use App\Models\User;
class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['title','slug', 'tags','photo','summary','content','cat_id','user_id','status'];

    public function tags()
    {
        // Lấy tất cả tag từ bảng 'tags' dựa trên id trong cột 'tags' (mảng JSON)
        $tagsArray = json_decode($this->tags, true);  // Chuyển 'tags' thành mảng

        // Lấy các tag từ bảng 'tags' với id có trong mảng
        return Tag::whereIn('id', $tagsArray)->get(); 
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Tcomments()
    {
        return $this->hasMany(TComment::class, 'item_id')
                    ->where('item_code', 'blog')
                    ->where('parent_id', 0)  // chỉ lấy bình luận cấp 1
                    ->orderBy('created_at', 'desc');
    }

    public function Tmotion()
    {
        return $this->hasOne(TMotionItem::class, 'item_id')
                    ->where('item_code', 'blog');
    }
}
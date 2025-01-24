<?php

namespace App\Modules\Blog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Tag\Models\Tag;
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
}
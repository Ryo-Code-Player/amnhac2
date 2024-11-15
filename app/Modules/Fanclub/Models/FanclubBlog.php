<?php

namespace App\Modules\Fanclub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Blog\Models\Blog;

class FanclubBlog extends Model
{
    use HasFactory;

    protected $fillable = ['fanclub_id', 'blog_id'];

    public function fanclub()
    {
        return $this->belongsTo(Fanclub::class);
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}

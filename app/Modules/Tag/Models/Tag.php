<?php

namespace App\Modules\Tag\Models;

use App\Modules\Resource\Models\Resource;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Blog\Models\Blog;
use App\Modules\MusicCompany\Models\MusicCompany;
use App\Modules\Singer\Models\Singer; // Đảm bảo import mô hình Singer
use App\Modules\Song\Models\Song; // Nạp thêm mô hình Song

class Tag extends Model
{
    protected $fillable = ['title'];

    public function resources()
    {
        return $this->hasMany(Resource::class, 'id', 'tag_id');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'id', 'tag_id');
    }

    public function musicCompanies()
    {
        return $this->hasMany(MusicCompany::class, 'id', 'tag_id');
    }
    

    public function singers()
    {
        return $this->hasMany(Singer::class, 'id', 'tag_id'); 
    }

    public function songs() 
    {
        return $this->hasMany(Song::class, 'id', 'tag_id'); 
    }
}

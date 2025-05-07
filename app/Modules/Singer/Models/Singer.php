<?php

namespace App\Modules\Singer\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Modules\Tag\Models\Tag;
use App\Modules\MusicCompany\Models\MusicCompany;
use App\Modules\MusicType\Models\MusicType;
use App\Modules\Song\Models\Song;

class Singer extends Model
{
    use HasFactory;

    protected $table = 'singers';

    protected $fillable = [
        'alias',
        'slug',
        'photo',
        'type_id',
        'summary',
        'content',
        'start_year',
        'tags',
        'status',
        'user_id',
        'company_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Tạo slug khi tạo mới
            $model->slug = Str::slug($model->alias);
        });

        static::updating(function ($model) {
            // Cập nhật slug nếu alias thay đổi
            if ($model->isDirty('alias')) {
                $model->slug = Str::slug($model->alias);
            }
        });
    }

    // Quan hệ với model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quan hệ với model MusicCompany
    public function company()
    {
        return $this->belongsTo(MusicCompany::class, 'company_id');
    }

  

    public function tags()
    {
        // Lấy tất cả tag từ bảng 'tags' dựa trên id trong cột 'tags' (mảng JSON)
        $tagsArray = json_decode($this->tags, true);  // Chuyển 'tags' thành mảng
    
        // Lấy các tag từ bảng 'tags' với id có trong mảng
        return Tag::whereIn('id', $tagsArray)->get(); 
    }
    public function musicType()
    {
    return $this->belongsTo(MusicType::class, 'type_id');
    }


    public function song()
    {
        return $this->hasMany(Song::class, 'singer_id');
    }
}

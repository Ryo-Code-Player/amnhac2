<?php

namespace App\Modules\MusicCompany\Models;
use App\Models\User; // Đảm bảo rằng đây là đường dẫn chính xác
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Thêm dòng này
use App\Modules\Resource\Models\Resource;
class MusicCompany extends Model
{
    use HasFactory;

    protected $table = 'music_companies';

    protected $fillable = [
        'title',
        'slug',
        'address',
        'photo',
        'summary',
        'content',
        'resources',
        'tags',
        'status',
        'phone',
        'email',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Tạo slug khi tạo mới
            $model->slug = Str::slug($model->title);
        });

        static::updating(function ($model) {
            // Kiểm tra nếu title đã được cập nhật thì mới tạo slug
            if ($model->isDirty('title')) {
                $model->slug = Str::slug($model->title);
            }
        });
    }
     // Mối quan hệ với model User
     public function user()
     {
         return $this->belongsTo(User::class, 'user_id');
     }

     public function resources()
     {
         return $this->belongsToMany(Resource::class, 'music_company_resource', 'music_company_id', 'resource_id');
     }
     
     
}

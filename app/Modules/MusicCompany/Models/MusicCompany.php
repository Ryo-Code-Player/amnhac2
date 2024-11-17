<?php

namespace App\Modules\MusicCompany\Models;

use App\Models\User; // Đảm bảo rằng đây là đường dẫn chính xác
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Thêm dòng này
use App\Modules\Resource\Models\Resource;
use App\Modules\Tag\Models\Tag;

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
        'resources', // Trường JSON để lưu tài nguyên
        'tags',
        'status',
        'phone',
        'email',
        'user_id',
    ];

    // Nếu bạn sử dụng JSON để lưu trữ resources
    protected $casts = [
        'resources' => 'array',
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
    // Lấy các tags từ cột 'tags' dưới dạng JSON
    public function tags()
    {
        // Lấy tất cả tag từ bảng 'tags' dựa trên id trong cột 'tags' (mảng JSON)
        $tagsArray = json_decode($this->tags, true);  // Chuyển 'tags' thành mảng
    
        // Lấy các tag từ bảng 'tags' với id có trong mảng
        return Tag::whereIn('id', $tagsArray)->get(); 
    }
    
    
    // Mối quan hệ với model Resource
    public function resources()
    {
        return $this->hasMany(Resource::class, 'id', 'resources->id'); // Cập nhật để khớp với cách lưu trữ JSON
    }

    
    // Hàm để thêm tài nguyên
    public function addResource(array $resourceData)
    {
        // Lấy tài nguyên hiện có từ trường JSON
        $resources = json_decode($this->resources, true) ?? [];
        $resources[] = $resourceData; // Thêm tài nguyên mới vào mảng

        // Cập nhật trường resources
        $this->resources = json_encode($resources);
        $this->save();
    }

    // Hàm để lấy tất cả tài nguyên
    public function getResourcesAttribute()
    {
        return json_decode($this->attributes['resources'], true);
    }
     /**
     * Tạo slug từ tiêu đề
     *
     * @param string $title
     * @return string
     */
    public function createSlug($title)
    {
        return Str::slug($title);
    }
    
}

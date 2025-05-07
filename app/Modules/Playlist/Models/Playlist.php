<?php

namespace App\Modules\Playlist\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Thêm dòng này

class Playlist extends Model
{
    use HasFactory;

    protected $table = 'playlists'; // Tên bảng playlist

    protected $fillable = [
        'title',
        'photo',
        'slug',
        'user_id', // ID của người dùng tạo playlist
        'song_id', // ID của bài hát trong playlist
        'order_id', // Thứ tự của bài hát trong playlist
        'type', // Loại playlist (ví dụ: cá nhân, công khai...)
    ];

    protected $casts = [
        'song_ids' => 'array', // Chuyển đổi song_ids từ JSON thành mảng khi truy xuất
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


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

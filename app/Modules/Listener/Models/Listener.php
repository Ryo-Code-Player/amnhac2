<?php

namespace App\Modules\Listener\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Singer\Models\Singer; 
use App\Modules\Composer\Models\Composer; 
use App\Modules\Song\Models\Song; 
class Listener extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'listeners';

    // Các cột có thể gán giá trị
    protected $fillable = [
        'favorite_type',     // Loại yêu thích (ví dụ: thể loại nhạc)
        'favorite_song',     // Bài hát yêu thích
        'favorite_singer',   // Ca sĩ yêu thích
        'favorite_composer', // Nhạc sĩ yêu thích
        'status',            // Trạng thái (hoạt động, không hoạt động...)
    ];

    // Các giá trị mặc định khi không được chỉ định
    protected $attributes = [
        'status' => 'active', // Trạng thái mặc định là "active"
    ];

    /**
     * Accessor để lấy status với mô tả
     *
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        return $this->status === 'active' ? 'Hoạt động' : 'Không hoạt động';
    }

    /**
     * Mutator để đảm bảo chuẩn hóa status khi lưu
     *
     * @param string $value
     * @return void
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtolower($value);
    }

    /**
     * Accessor để định dạng các trường yêu thích thành danh sách
     *
     * @return array
     */
    public function getFavoritesAttribute()
    {
        return [
            'type'     => $this->favorite_type,
            'song'     => $this->favorite_song,
            'singer'   => $this->favorite_singer,
            'composer' => $this->favorite_composer,
        ];
    }
    
    // Mối quan hệ với bảng songs (favorite_song)
    public function favoriteSong()
    {
        return $this->belongsTo(Song::class, 'favorite_song');
    }

    // Mối quan hệ với bảng singers (favorite_singer)
    public function favoriteSinger()
    {
        return $this->belongsTo(Singer::class, 'favorite_singer');
    }

    // Mối quan hệ với bảng composers (favorite_composer)
    public function favoriteComposer()
    {
        return $this->belongsTo(Composer::class, 'favorite_composer');
    }
}

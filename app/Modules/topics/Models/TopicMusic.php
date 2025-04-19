<?php

namespace App\Modules\topics\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Modules\Song\Models\Song;
use App\Modules\topics\Models\topics;

class TopicMusic extends Model
{
    use HasFactory;

    protected $table = 'topic_music'; // Tên bảng

    protected $fillable = ['topic_id', 'song_id']; // Các cột có thể gán giá trị

    /**
     * Thiết lập giá trị mặc định cho model
     */
    public function topics()
    {
        return $this->belongsTo(topics::class);
    }
    
    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    /**
     * Lấy danh sách các topic đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

<?php

namespace App\Modules\topics\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class topics extends Model
{
    use HasFactory;

    protected $table = 'topics'; // Tên bảng

    protected $fillable = ['title', 'slug', 'status']; // Các cột có thể gán giá trị

    /**
     * Thiết lập giá trị mặc định cho model
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($topic) {
            $topic->slug = Str::slug($topic->title);
        });
    }

    /**
     * Lấy danh sách các topic đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

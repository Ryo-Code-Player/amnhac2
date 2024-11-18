<?php
namespace App\Modules\Composer\Models;
use App\Models\User; // Đảm bảo rằng đây là đường dẫn chính xác
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composer extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'slug',
        'status',
        'summary',
        'content',
        'photo',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

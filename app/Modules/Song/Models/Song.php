<?php

namespace App\Modules\Song\Models;

use App\Modules\Composer\Models\Composer;
use App\Modules\MusicType\Models\MusicType;
use App\Modules\Singer\Models\Singer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Thêm dòng này
use App\Modules\Resource\Models\Resource;
use App\Modules\Tag\Models\Tag;

class Song extends Model
{
    use HasFactory;

    protected $table = 'songs'; // Tên bảng song

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'resources', // Trường JSON để lưu tài nguyên
        'tags',
        'status',
        'musictype_id',
        'composer_id', // Id của tác giả sáng tác
        'singer_id',   // Id của ca sĩ thể hiện
        'view',
    ];

    // Nếu bạn sử dụng JSON để lưu trữ resources
    protected $casts = [
        'resources' => 'array',
    ];
    
    protected $appends = ['resourcesSong'];

    public function getResourcesSongAttribute(){
        
        $resourcesArray = is_string($this->resources) ? json_decode($this->resources, true) : $this->resources;
       
        // Lấy tất cả các resource_id từ mảng resources đã gắn vào bài hát
        $resourceIds = array_column($resourcesArray, 'resource_id');
    
        // Nếu có resource_id, lấy danh sách tài nguyên đã gắn cho bài hát
        $resources = Resource::where('id', $resourceIds)->get();
        // dd($resources);
        return $resources;
        // Lấy danh sách tài nguyên có thể thêm vào (tức là chưa được gắn)
        // $availableResources = Resource::whereNotIn('id', $resourceIds)->get();

        // return $availableResources;
    }

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

    // Mối quan hệ với model User (composer)
// Song model
public function composer()
{
    return $this->belongsTo(Composer::class, 'composer_id'); // Hoặc tương tự, tùy vào cách bạn đặt tên bảng và cột.
}

public function singer()
{
    return $this->belongsTo(Singer::class, 'singer_id'); // Tương tự như trên
}

public function musictype()
{
    return $this->belongsTo(MusicType::class, 'musictype_id'); // Tương tự như trên
}


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
        return $this->hasMany(Resource::class, 'id', 'resources->id');
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

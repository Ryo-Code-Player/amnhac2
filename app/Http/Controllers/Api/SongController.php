<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Song\Models\Song;

class SongController extends Controller
{
    public function getsong()
    {
        // Lấy danh sách bài hát
        $songs = Song::all();
    
        if ($songs->isEmpty()) {
            return response()->json(['message' => 'Không có bài hát nào.'], 404);
        }
    
        // Xử lý từng bài hát
        $songs = $songs->map(function ($song) {
            // Giải mã JSON từ cột resources
            $resourceIds = collect(json_decode($song->resources, true))->pluck('resource_id')->toArray();
    
            // Truy vấn các tài nguyên từ bảng resources
            $resources = \DB::table('resources')
                ->whereIn('id', $resourceIds)
                ->get()
                ->map(function ($resource) {
                    return [
                        'id' => $resource->id,
                        'title' => $resource->title,
                        'url' => $resource->url,
                        'file_type' => $resource->file_type,
                        'created_at' => $resource->created_at,
                        'updated_at' => $resource->updated_at,
                    ];
                });
    
            return [
                'id' => $song->id,
                'title' => $song->title,
                'resources' => $resources,
                'tags' => $song->tags ? json_decode($song->tags, true) : [],
                'composer_id' => $song->composer_id,
                'singer_id' => $song->singer_id,
                'created_at' => $song->created_at->toDateTimeString(),
                'updated_at' => $song->updated_at->toDateTimeString(),
            ];
        });
    
        return response()->json($songs);
    }
    
}

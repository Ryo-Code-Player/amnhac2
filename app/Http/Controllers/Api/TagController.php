<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Tag\Models\Tag; 
 // Đảm bảo bạn đã import model Tag
use Illuminate\Http\Request;
class TagController extends Controller
{
    // Lấy danh sách tất cả các tags
    public function gettag()
    {
        // Lấy tất cả các tag từ cơ sở dữ liệu
        $tags = Tag::all();

        // Kiểm tra nếu có tags
        if ($tags->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No tags found'
            ], 404);
        }

        // Trả về danh sách tags dưới dạng JSON
        return response()->json([
            'success' => true,
            'data' => $tags
        ]);
    }
}

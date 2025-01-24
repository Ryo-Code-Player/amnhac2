<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Resource\Models\Resource;

class ResourcesController extends Controller
{
    // Hàm lấy chi tiết resource theo ID
    public function getresources($id)
    {
        // Tìm resource theo ID
        $resource = Resource::find($id);

        if ($resource) {
            return response()->json([
                'success' => true,
                'data' => $resource
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found'
            ], 404);
        }
    }
}

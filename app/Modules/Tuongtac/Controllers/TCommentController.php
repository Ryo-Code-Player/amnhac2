<?php
namespace App\Modules\Tuongtac\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\TuongTac\Models\TComment;
use App\Modules\Blog\Models\Blog;
use Illuminate\Support\Facades\Auth;

class TCommentController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Bạn cần đăng nhập để bình luận.'], 401);
        }

        $request->validate([
            'item_id'   => 'required|integer|exists:blogs,id',
            'content'   => 'required|string|max:1000',
            'parent_id' => 'nullable|integer|exists:t_comments,id'
        ]);

        $comment = TComment::create([
            'item_id'   => $request->item_id,
            'item_code' => 'blog',
            'user_id'   => Auth::id(),
            'content'   => $request->content,
            'parent_id' => $request->parent_id ?? 0,
            'status'    => 'active'
        ]);

        return response()->json([
            'success' => true,
            'comment' => $comment->load('user'),
            'message' => 'Bình luận đã được thêm!'
        ]);
    }
}


?>
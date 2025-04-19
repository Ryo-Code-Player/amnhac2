<?php
namespace App\Modules\Tuongtac\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\TuongTac\Models\TMotionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Modules\Blog\Models\Blog;
use App\Modules\TuongTac\Models\TComment;

class TMotionItemController extends Controller
{
    public function toggle(Request $request)
    {
        // Kiểm tra xác thực người dùng
        if (!Auth::check()) {
            Log::warning('Toggle failed: User not authenticated');
            return response()->json(['error' => 'Bạn cần đăng nhập để thực hiện hành động này.'], 401);
        }

        try {
            // Validate dữ liệu đầu vào
            $request->validate([
                'item_id'   => 'required|integer|min:1',
                'item_code' => 'required|string|in:blog,comment'
            ]);

            $item_id = $request->item_id;
            $item_code = $request->item_code;
            $userId = Auth::id();

            // Ghi log dữ liệu đầu vào
            Log::info('Toggle like attempt', [
                'item_id' => $item_id,
                'item_code' => $item_code,
                'user_id' => $userId
            ]);

            // Kiểm tra item_id tồn tại
            if ($item_code === 'blog' && !Blog::find($item_id)) {
                Log::error('Toggle failed: Blog not found', ['item_id' => $item_id]);
                return response()->json(['error' => 'Bài viết không tồn tại.'], 404);
            }
            if ($item_code === 'comment' && !TComment::find($item_id)) {
                Log::error('Toggle failed: Comment not found', ['item_id' => $item_id]);
                return response()->json(['error' => 'Bình luận không tồn tại.'], 404);
            }

            // Tìm bản ghi TMotionItem
            $motion = TMotionItem::where('item_id', $item_id)
                ->where('item_code', $item_code)
                ->first();

            $liked = false;
            if (!$motion) {
                // Tạo mới nếu chưa có
                $motion = TMotionItem::create([
                    'item_id'      => $item_id,
                    'item_code'    => $item_code,
                    'motions'      => json_encode(['likes' => 1]),
                    'user_motions' => json_encode([$userId])
                ]);
                $liked = true;
                Log::info('New motion created', [
                    'motion_id' => $motion->id,
                    'item_id' => $item_id,
                    'item_code' => $item_code
                ]);
            } else {
                // Cập nhật bản ghi hiện có
                $userMotions = json_decode($motion->user_motions, true) ?? [];
                $motions = json_decode($motion->motions, true) ?? ['likes' => 0];
                $currentLikes = $motions['likes'] ?? 0;

                if (in_array($userId, $userMotions)) {
                    // Unlike: Xóa userId và giảm lượt like
                    $userMotions = array_values(array_diff($userMotions, [$userId]));
                    $currentLikes = max(0, $currentLikes - 1);
                    $liked = false;
                } else {
                    // Like: Thêm userId và tăng lượt like
                    $userMotions[] = $userId;
                    $currentLikes++;
                    $liked = true;
                }

                // Cập nhật bản ghi
                $motion->update([
                    'motions'      => json_encode(['likes' => $currentLikes]),
                    'user_motions' => json_encode($userMotions)
                ]);

                Log::info('Motion updated', [
                    'motion_id' => $motion->id,
                    'item_id' => $item_id,
                    'item_code' => $item_code,
                    'likes' => $currentLikes,
                    'liked' => $liked
                ]);
            }

            // Trả về phản hồi JSON
            return response()->json([
                'status' => $liked ? 'liked' : 'unliked',
                'likes'  => json_decode($motion->motions, true)['likes'],
                'liked'  => $liked
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Toggle validation error', [
                'errors' => $e->errors(),
                'item_id' => $request->item_id,
                'item_code' => $request->item_code
            ]);
            return response()->json(['error' => 'Dữ liệu đầu vào không hợp lệ.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Toggle error', [
                'message' => $e->getMessage(),
                'item_id' => $request->item_id,
                'item_code' => $request->item_code,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Có lỗi xảy ra khi xử lý like.'], 500);
        }
    }

    public function check(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['liked' => false]);
        }

        $request->validate([
            'item_id'   => 'required|integer',
            'item_code' => 'required|string|in:blog,comment'
        ]);

        $motion = TMotionItem::where('item_id', $request->item_id)
            ->where('item_code', $request->item_code)
            ->first();

        $liked = false;
        if ($motion) {
            $userMotions = json_decode($motion->user_motions, true) ?? [];
            $liked = in_array(Auth::id(), $userMotions);
        }

        return response()->json(['liked' => $liked]);
    }
}

?>
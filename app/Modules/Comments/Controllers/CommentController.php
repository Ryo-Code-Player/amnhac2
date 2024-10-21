<?php

namespace App\Modules\Comments\Controllers;

use App\Modules\Comments\Models\Comment; // Kiểm tra xem Model Comment đã được import đúng
use App\Modules\Comments\Models\Item;    // Kiểm tra xem Model Item đã được import đúng
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Đảm bảo import lớp Controller đúng cách

class CommentController extends Controller
{
    protected $pagesize = 10;

    public function __construct()
    {
        $this->middleware('auth'); // Yêu cầu đăng nhập cho admin
    }

    // Hiển thị danh sách bình luận trong admin
    public function index()
    {
       
        // Lấy tất cả bình luận từ cơ sở dữ liệu và phân trang
        $comments = Comment::with('user', 'item', 'children') // Sửa 'replies' thành 'children'
                           ->orderBy('created_at', 'DESC')
                           ->paginate($this->pagesize);

           // Định nghĩa biến active_menu
    $active_menu = 'comments'; // Thay đổi giá trị này tùy thuộc vào menu hiện tại

    // Thay đổi đường dẫn view để phù hợp với cấu trúc thư mục
    return view('Comments::comments.index', compact('comments', 'active_menu'));
    }

    // Xóa bình luận
    public function destroy($id)
    {
        // Lấy comment cha
        $comment = Comment::findOrFail($id);
    
        // Kiểm tra xem comment có comment con hay không
        if (Comment::where('parent_id', $comment->id)->exists()) {
            return redirect()->back()->withErrors('Không thể xóa comment này vì nó có comment con.');
        }
    
        // Nếu không có comment con, xóa comment cha
        $comment->delete();
    
        return redirect()->route('admin.comments.index')->with('success', 'Comment đã được xóa thành công');
    }
    
    
    
    
    
    
    
    
    

    // Cập nhật trạng thái bình luận
// Cập nhật trạng thái bình luận
public function updateStatus(Request $request)
{
    $request->validate([
        'id' => 'required|integer|exists:comments,id',
        'mode' => 'required|boolean',
    ]);

    $comment = Comment::find($request->id);
    $comment->status = $request->mode ? 'active' : 'inactive'; // Cập nhật trạng thái
    $comment->save();

    return response()->json(['msg' => 'Cập nhật trạng thái thành công!']);
}



    public function edit($id)
{
    // Lấy bình luận theo ID
    $comment = Comment::findOrFail($id);
    
    $active_menu = 'comment_edit'; // Đặt giá trị cho biến active_menu

    // Trả về view để chỉnh sửa bình luận
    return view('Comments::comments.edit', compact('comment', 'active_menu')); // Truyền biến đến view
}

public function update(Request $request, $id)
{
    $comment = Comment::findOrFail($id);
    $comment->content = $request->input('content');
    $comment->save();

    return redirect()->route('admin.comments.index')->with('success', 'Comment updated successfully!');
}


}

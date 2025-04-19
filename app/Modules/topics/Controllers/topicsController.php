<?php

namespace App\Modules\topics\Controllers;

use Illuminate\Http\Request;
use App\Modules\topics\Models\topics;
use App\Http\Controllers\Controller;

class topicsController extends Controller
{

    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','10');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $active_menu = "topic_manage";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách chủ đề Âm nhạc</li>';

        $topic_manage = topics::paginate(10); // Thay đổi từ MusicCompany sang MusicType
        return view('topics::topic.index', compact('topic_manage', 'active_menu', 'breadcrumb'));
    }

    public function create()
    {
        
        $active_menu = "topic_create"; // Xác định trạng thái menu hiện tại
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.topic.index') . '">Danh sách chủ đề âm nhạc</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm chủ đề Âm Nhạc</li>';

        return view('topics::topic.create', compact('active_menu', 'breadcrumb'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:music_types', // Kiểm tra slug là duy nhất
            'status' => 'required|string|in:active,inactive',
        ]);

        // Tạo slug tự động nếu không có
        $validatedData['slug'] = $validatedData['slug'] ?? $this->createSlug($validatedData['title']);

        // Lưu vào cơ sở dữ liệu
        topics::create($validatedData);

        // Chuyển hướng về danh sách thể loại âm nhạc với thông báo thành công
        return redirect()->route('admin.topic.index')->with('success', 'Chủ đề âm nhạc đã được thêm thành công.');
    }

    public function edit($id)
    {
        $active_menu = "topic_edit"; // Xác định trạng thái menu hiện tại
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.musictype.index') . '">Danh sách chủ đề âm nhạc</a></li>
            <li class="breadcrumb-item active" aria-current="page">Điều chỉnh chủ đề Âm Nhạc</li>';

        // Tìm thể loại âm nhạc
        $topic_edit = topics::findOrFail($id);

        return view('topics::topic.edit', compact('topic_edit', 'active_menu', 'breadcrumb'));
    }

    public function update(Request $request, $id)
    {
        // Tìm thể loại âm nhạc theo ID
        $topic_update = topics::findOrFail($id);

        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:music_types,slug,' . $topic_update->id, // Kiểm tra slug là duy nhất
            'status' => 'required|string|in:active,inactive',
        ]);

        // Cập nhật slug nếu tiêu đề đã thay đổi
        if ($topic_update->title !== $request->title) {
            $validatedData['slug'] = $this->createSlug($request->title);
        }

        // Cập nhật các thuộc tính khác
        $topic_update->update($validatedData);

        // Chuyển hướng với thông báo thành công
        return redirect()->route('admin.topic.index')->with('success', 'Chủ đề âm nhạc đã được cập nhật thành công.');
    }

    public function destroy(string $id)
    {
        $topic_destroy = topics::find($id);

        if ($topic_destroy) {
            // Xóa bản ghi thể loại âm nhạc
            $topic_destroy->delete();
            return redirect()->route('admin.topic.index')->with('success', 'Xóa chủ đề âm nhạc thành công!');
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu chủ đề âm nhạc!');
        }
    }

    public function show($id)
    {
        $active_menu = "topic_show";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.musictype.index') . '">Danh sách thể loại âm nhạc</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chi Tiết Thể Loại Âm Nhạc</li>';

        $topic_show = topics::findOrFail($id);

        return view('topics::topic.show', compact('topic_show', 'active_menu', 'breadcrumb'));
    }

    protected function createSlug($title)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }
}
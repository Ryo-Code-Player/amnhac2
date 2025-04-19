<?php

namespace App\Modules\topics\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\topics\Models\TopicMusic;
use App\Modules\Fanclub\Models\FanclubItem;


class TopicMusicController extends Controller
{

    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','10');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $active_menu = "topic_music_manage";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách âm nhạc của chủ đề</li>';

        $topic_music_manage = TopicMusic::paginate(10); // Thay đổi từ MusicCompany sang MusicType
        return view('TopicMusic::topic_music.index', compact('topic_music_manage', 'active_menu', 'breadcrumb'));
    }

    public function create()
    {
        
        $active_menu = "topic_music_create"; // Xác định trạng thái menu hiện tại
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.topic.index') . '">Danh sách âm nhạc của chủ đề</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm âm nhạc vào chủ đề</li>';

        return view('TopicMusic::topic_music.create', compact('active_menu', 'breadcrumb'));
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
        TopicMusic::create($validatedData);

        // Chuyển hướng về danh sách thể loại âm nhạc với thông báo thành công
        return redirect()->route('admin.topic.index')->with('success', 'Đã thêm thành công.');
    }

    public function edit($id)
    {
        $active_menu = "topic_music_edit"; // Xác định trạng thái menu hiện tại
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.musictype.index') . '">Danh sách âm nhạc của chủ đề</a></li>
            <li class="breadcrumb-item active" aria-current="page">Điều chỉnh</li>';

        // Tìm thể loại âm nhạc
        $topic_music_edit = TopicMusic::findOrFail($id);

        return view('TopicMusic::topic_music.edit', compact('topic_music_edit', 'active_menu', 'breadcrumb'));
    }

    public function update(Request $request, $id)
    {
        // Tìm thể loại âm nhạc theo ID
        $topic_music_update = TopicMusic::findOrFail($id);

        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:music_types,slug,' . $topic_music_update->id, // Kiểm tra slug là duy nhất
            'status' => 'required|string|in:active,inactive',
        ]);

        // Cập nhật slug nếu tiêu đề đã thay đổi
        if ($topic_music_update->title !== $request->title) {
            $validatedData['slug'] = $this->createSlug($request->title);
        }

        // Cập nhật các thuộc tính khác
        $topic_music_update->update($validatedData);

        // Chuyển hướng với thông báo thành công
        return redirect()->route('admin.topic.index')->with('success', 'Chủ đề âm nhạc đã được cập nhật thành công.');
    }

    public function destroy(string $id)
    {
        $topic_music_destroy = TopicMusic::find($id);

        if ($topic_music_destroy) {
            // Xóa bản ghi thể loại âm nhạc
            $topic_music_destroy->delete();
            return redirect()->route('admin.topic.index')->with('success', 'Xóa chủ đề âm nhạc thành công!');
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu chủ đề âm nhạc!');
        }
    }

    public function show($id)
    {
        $active_menu = "topic_music_show";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.musictype.index') . '">Danh sách thể loại âm nhạc</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chi Tiết Thể Loại Âm Nhạc</li>';

        $topic_show = TopicMusic::findOrFail($id);

        return view('TopicMusic::topic_music.show', compact('topic_show', 'active_menu', 'breadcrumb'));
    }

    protected function createSlug($title)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }
}
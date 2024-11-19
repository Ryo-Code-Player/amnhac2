<?php

namespace App\Modules\Event\Controllers;

use App\Modules\Event\Models\EventType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventTypeController extends Controller
{
    protected $pagesize;
    public function index()
    {
        $func = "eventtype_list";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }
        
        $active_menu = "eventtype_list";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách loại sự kiện</li>';

        $eventTypes = EventType::orderBy('id', 'DESC')->paginate($this->pagesize);
        return view('Event::eventtype.index', compact('eventTypes', 'breadcrumb', 'active_menu'));
    }

    // Hiển thị form tạo mới EventType
    public function create()
    {
        $func = "eventtype_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $active_menu = "eventtype_add";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="' . route('admin.EventType.index') . '">Danh sách loại sự kiện</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tạo loại sự kiện</li>';

        return view('Event::EventType.create', compact('breadcrumb', 'active_menu'));
    }

    // Xử lý lưu EventType
    public function store(Request $request)
    {
        $func = "eventtype_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        // Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // Tạo slug từ title
        $slug = Str::slug($request->title);
        
        // Kiểm tra slug trùng lặp
        $slugCount = EventType::where('slug', $slug)->count();
        if ($slugCount > 0) {
            $slug = $slug . '-' . ($slugCount + 1);
        }

        // Lưu dữ liệu EventType
        $data = $request->all();
        $data['slug'] = $slug;

        EventType::create($data);

        return redirect()->route('admin.EventType.index')->with('success', 'Tạo loại sự kiện thành công!');
    }

    // Hiển thị form chỉnh sửa EventType
    public function edit($id)
    {
        $func = "eventtype_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $eventType = EventType::find($id);
        if (!$eventType) {
            return back()->with('error', 'Không tìm thấy loại sự kiện');
        }

        $active_menu = "eventtype_edit";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="' . route('admin.EventType.index') . '">Danh sách loại sự kiện</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sửa loại sự kiện</li>';

        return view('Event::EventType.edit', compact('eventType', 'breadcrumb', 'active_menu'));
    }

    // Xử lý cập nhật EventType
    public function update(Request $request, $id)
    {
        $func = "eventtype_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $eventType = EventType::find($id);
        if (!$eventType) {
            return back()->with('error', 'Không tìm thấy loại sự kiện');
        }

        // Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // Tạo slug mới nếu title thay đổi
        $slug = Str::slug($request->title);

        // Kiểm tra slug trùng lặp và đảm bảo tính duy nhất
        $slugCount = EventType::where('slug', $slug)->where('id', '!=', $id)->count();
        if ($slugCount > 0) {
            $slug = $slug . '-' . ($slugCount + 1);
        }

        // Cập nhật dữ liệu EventType
        $eventType->update([
            'title' => $request->title,
            'slug' => $slug,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.EventType.index')->with('success', 'Cập nhật loại sự kiện thành công!');
    }

    // Xử lý xóa EventType
    public function destroy($id)
    {
        $func = "eventtype_delete";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $eventType = EventType::find($id);
        if (!$eventType) {
            return back()->with('error', 'Không tìm thấy loại sự kiện');
        }

        $eventType->delete();

        return redirect()->route('admin.EventType.index')->with('success', 'Xóa loại sự kiện thành công!');
    }
}
<?php

namespace App\Modules\Event\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Event\Models\Event;
use App\Modules\Event\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    protected $pagesize;

    // Hiển thị danh sách các sự kiện
    public function index()
    {
        $func = "event_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $active_menu = "event_list";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách sự kiện</li>';

        $events = Event::with('eventType')->orderBy('id', 'DESC')->paginate($this->pagesize);
        return view('Event::Event.index', compact('events', 'breadcrumb', 'active_menu'));
    }

    // Hiển thị form tạo mới sự kiện
    public function create()
    {
        $func = "event_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $active_menu = "event_add";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="'.route('admin.Event.index').'">Danh sách sự kiện</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tạo sự kiện mới</li>';
        
        $eventtype = EventType::orderBy('id', 'DESC')->paginate($this->pagesize);

        return view('Event::Event.create', compact('breadcrumb', 'active_menu', 'eventtype'));
    }

    // Lưu sự kiện mới
    public function store(Request $request)
    {
        $func = "event_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        // Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'photo' => 'string|nullable',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'timestart' => 'required|date',
            'timeend' => 'required|date|after:timestart',
            'diadiem' => 'nullable|string',
        ]);

        // Tạo slug từ title
        $slug = Str::slug($request->title);
        
        // Kiểm tra slug trùng lặp
        $slugCount = Event::where('slug', $slug)->count();
        if ($slugCount > 0) {
            $slug = $slug . '-' . ($slugCount + 1);
        }

        // Tạo một sự kiện mới
        $data = $request->all();
        $data['slug'] = $slug;

        Event::create($data);

        return redirect()->route('admin.Event.index')->with('success', 'Tạo sự kiện thành công!');
    }

    // Hiển thị form chỉnh sửa sự kiện
    public function edit($id)
    {
        $func = "event_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $event = Event::find($id);
        if(!$event)
        {
            return back()->with('error', 'Không tìm thấy sự kiện');
        }

        $active_menu = "event_edit";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="'.route('admin.Event.index').'">Danh sách sự kiện</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa sự kiện</li>';

        $eventtype = EventType::orderBy('id', 'DESC')->paginate($this->pagesize);
        return view('Event::Event.edit', compact('breadcrumb', 'event', 'active_menu', 'eventtype'));
    }

    // Cập nhật sự kiện
    public function update(Request $request, $id)
    {
        $func = "event_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $event = Event::find($id);
        if(!$event)
        {
            return back()->with('error', 'Không tìm thấy sự kiện');
        }

        // Validate input
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'photo' => 'string|nullable',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'timestart' => 'required|date',
            'timeend' => 'required|date|after:timestart',
            'diadiem' => 'nullable|string',
        ]);

        // Tạo slug mới nếu title thay đổi
        $slug = Str::slug($request->title);

        // Kiểm tra slug trùng lặp và đảm bảo tính duy nhất
        $slugCount = Event::where('slug', $slug)->where('id', '!=', $id)->count();
        if ($slugCount > 0) {
            $slug = $slug . '-' . ($slugCount + 1);
        }

        $validatedData['photo'] = $validatedData['photo'] ?? $event->photo;
        if (empty($validatedData['photo'])) {
            $validatedData['photo'] = asset('backend/images/profile-6.jpg');
        }

        $event->fill($validatedData);
        $status = $event->save();

        if ($status) {
            return redirect()->route('admin.Event.index')->with('success', 'Cập nhật thành công');
        } else {
            return back()->with('error', 'Có lỗi xảy ra!');
        }
    }

    // Xóa sự kiện
    public function destroy($id)
    {
        $func = "event_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $event = Event::find($id);
        if(!$event)
        {
            return back()->with('error', 'Không tìm thấy sự kiện');
        }

        $event->delete();

        return redirect()->route('admin.Event.index')->with('success', 'Xóa sự kiện thành công!');
    }
}

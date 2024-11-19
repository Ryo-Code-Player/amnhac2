<?php

namespace App\Modules\Event\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Event\Models\EventGroup;
use App\Modules\Group\Models\Group;
use App\Modules\Event\Models\Event;
use Illuminate\Http\Request;

class EventGroupController extends Controller
{
    protected $pagesize;

    // Hiển thị danh sách các nhóm sự kiện
    public function index()
    {
        $func = "event_group_list";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $active_menu = "event_group_list";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách nhóm sự kiện</li>';

        // Lấy danh sách nhóm sự kiện
        $eventGroups = EventGroup::with(['group', 'event'])->orderBy('id', 'DESC')->paginate($this->pagesize);

        return view('Event::EventGroup.index', compact('eventGroups', 'breadcrumb', 'active_menu'));
    }

    // Hiển thị form tạo mới nhóm sự kiện
    public function create()
    {
        $func = "event_group_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $active_menu = "event_group_add";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="'.route('admin.EventGroup.index').'">Danh sách nhóm sự kiện</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm nhóm sự kiện</li>';

        // Lấy danh sách nhóm và sự kiện
        $groups = Group::orderBy('id', 'DESC')->paginate($this->pagesize);
        $events = Event::orderBy('id', 'DESC')->paginate($this->pagesize);

        return view('Event::EventGroup.create', compact('breadcrumb', 'active_menu', 'groups', 'events'));
    }

    // Lưu nhóm sự kiện mới
    public function store(Request $request)
    {
        $func = "event_group_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        // Validate input
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'event_id' => 'required|exists:events,id',
        ]);

        // Tạo mới nhóm sự kiện
        EventGroup::create($request->all());

        return redirect()->route('admin.EventGroup.index')->with('success', 'Thêm nhóm sự kiện thành công!');
    }

    // Hiển thị form chỉnh sửa nhóm sự kiện
    public function edit($id)
    {
        $func = "event_group_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $eventGroup = EventGroup::find($id);
        if (!$eventGroup) {
            return back()->with('error', 'Không tìm thấy nhóm sự kiện');
        }

        $active_menu = "event_group_edit";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="'.route('admin.EventGroup.index').'">Danh sách nhóm sự kiện</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa nhóm sự kiện</li>';

        // Lấy danh sách nhóm và sự kiện
        $groups = Group::orderBy('id', 'DESC')->paginate($this->pagesize);
        $events = Event::orderBy('id', 'DESC')->paginate($this->pagesize);

        return view('Event::EventGroup.edit', compact('breadcrumb', 'active_menu', 'eventGroup', 'groups', 'events'));
    }

    // Cập nhật thông tin nhóm sự kiện
    public function update(Request $request, $id)
    {
        $func = "event_group_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $eventGroup = EventGroup::find($id);
        if (!$eventGroup) {
            return back()->with('error', 'Không tìm thấy nhóm sự kiện');
        }

        // Validate input
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'event_id' => 'required|exists:events,id',
        ]);

        // Cập nhật thông tin nhóm sự kiện
        $eventGroup->update($request->all());

        return redirect()->route('admin.EventGroup.index')->with('success', 'Cập nhật nhóm sự kiện thành công!');
    }

    // Xóa nhóm sự kiện
    public function destroy($id)
    {
        $func = "event_group_delete";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $eventGroup = EventGroup::find($id);
        if (!$eventGroup) {
            return back()->with('error', 'Không tìm thấy nhóm sự kiện');
        }

        $eventGroup->delete();

        return redirect()->route('admin.EventGroup.index')->with('success', 'Xóa nhóm sự kiện thành công!');
    }
}

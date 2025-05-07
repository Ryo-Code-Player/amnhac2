<?php

namespace App\Modules\Event\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Event\Models\EventUser;
use App\Models\User;
use App\Modules\Event\Models\Event;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventUserController extends Controller
{
    protected $pagesize;

    // Hiển thị danh sách người dùng tham gia sự kiện
    public function index()
    {
        $func = "event_user_list";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $active_menu = "event_user_list";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách người tham gia sự kiện</li>';

        // Lấy danh sách người tham gia sự kiện
        $eventUsers = EventUser::with(['user', 'event', 'role'])->orderBy('id', 'DESC')->paginate($this->pagesize);

        return view('Event::EventUser.index', compact('eventUsers', 'breadcrumb', 'active_menu'));
    }

    // Hiển thị form tạo mới người tham gia sự kiện
    public function create()
    {
        $func = "event_user_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $active_menu = "event_user_add";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="'.route('admin.EventUser.index').'">Danh sách người tham gia</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm người tham gia sự kiện</li>';

        // Lấy danh sách sự kiện, người dùng và vai trò
        $users = User::orderBy('id', 'DESC')->paginate($this->pagesize);
        $roles = Role::orderBy('id', 'DESC')->paginate($this->pagesize);
        $events = Event::orderBy('id', 'DESC')->paginate($this->pagesize);

        return view('Event::EventUser.create', compact('breadcrumb', 'active_menu', 'events', 'users', 'roles'));
    }

    // Lưu người tham gia sự kiện mới
    public function store(Request $request)
    {
        $func = "event_user_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }
        $request->merge(['code' => Str::upper(Str::random(10))]);

        // Validate input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'role_id' => 'nullable|exists:roles,id',
            'vote' => 'nullable|integer',
            'code' => 'required'
         ]);

        // Tạo mới người tham gia sự kiện
        EventUser::create($request->all());

        return redirect()->route('admin.EventUser.index')->with('success', 'Thêm người tham gia sự kiện thành công!');
    }

    // Hiển thị form chỉnh sửa người tham gia sự kiện
    public function edit($id)
    {
        $func = "event_user_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $eventUser = EventUser::find($id);
        if (!$eventUser) {
            return back()->with('error', 'Không tìm thấy người tham gia sự kiện');
        }

        $active_menu = "event_user_edit";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="'.route('admin.EventUser.index').'">Danh sách người tham gia</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa người tham gia sự kiện</li>';

        // Lấy danh sách sự kiện, người dùng và vai trò
        $users = User::orderBy('id', 'DESC')->paginate($this->pagesize);
        $roles = Role::orderBy('id', 'DESC')->paginate($this->pagesize);
        $events = Event::orderBy('id', 'DESC')->paginate($this->pagesize);

        return view('Event::EventUser.edit', compact('breadcrumb', 'active_menu', 'eventUser', 'events', 'users', 'roles'));
    }

    // Cập nhật thông tin người tham gia sự kiện
    public function update(Request $request, $id)
    {
        $func = "event_user_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $eventUser = EventUser::find($id);
        if (!$eventUser) {
            return back()->with('error', 'Không tìm thấy người tham gia sự kiện');
        }

        // Validate input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'role_id' => 'nullable|exists:roles,id',
            'vote' => 'nullable|integer',
        ]);

        // Cập nhật thông tin người tham gia sự kiện
        $eventUser->update($request->all());

        return redirect()->route('admin.EventUser.index')->with('success', 'Cập nhật người tham gia sự kiện thành công!');
    }

    // Xóa người tham gia sự kiện
    public function destroy($id)
    {
        $func = "event_user_delete";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $eventUser = EventUser::find($id);
        if (!$eventUser) {
            return back()->with('error', 'Không tìm thấy người tham gia sự kiện');
        }

        $eventUser->delete();

        return redirect()->route('admin.EventUser.index')->with('success', 'Xóa người tham gia sự kiện thành công!');
    }
}

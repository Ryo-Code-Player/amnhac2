<?php

namespace App\Modules\Fanclub\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Fanclub\Models\FanclubUser;
use App\Modules\Fanclub\Models\Fanclub;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FanclubUserController extends Controller
{
    protected $pagesize;
    public function index()
    {

        $func = "fanclub_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="fanclub_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách thành viên </li>';
        $fanclubuser = FanclubUser::with(['user', 'role', 'fanclub'])
            ->orderBy('id', 'DESC')
            ->paginate($this->pagesize);
        return view('Fanclub::FanclubUser.index',compact('fanclubuser','breadcrumb','active_menu'));
    }

    // Hiển thị form tạo mới FanclubUser
    public function create()
    {
        $func = "fanclub_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $active_menu = "fanclub_add";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="' . route('admin.FanclubUser.index') . '">Danh sách thành viên</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm thành viên</li>';
        
        // Lấy dữ liệu từ các bảng user, fanclub, và role
        // $users = User::all();
        // $fanclubs = Fanclub::all();
        // $roles = Role::all();

        $users=User::orderBy('id','DESC')->paginate($this->pagesize);
        $fanclubs=Fanclub::orderBy('id','DESC')->paginate($this->pagesize);
        $roles=Role::orderBy('id','DESC')->paginate($this->pagesize);

        return view('Fanclub::FanclubUser.create', compact('breadcrumb', 'active_menu', 'users', 'fanclubs', 'roles'));
    }

    // Lưu FanclubUser mới
    public function store(Request $request)
    {
        $func = "fanclub_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        // Validate input
        $this->validate($request, [
            'user_id' => 'required|integer|exists:users,id',
            'fanclub_id' => 'required|integer|exists:fanclubs,id',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        // Tạo mới thành viên câu lạc bộ
        $fanclubUser = new FanclubUser();
        $fanclubUser->user_id = $request->user_id;
        $fanclubUser->fanclub_id = $request->fanclub_id;
        $fanclubUser->role_id = $request->role_id;

        if ($fanclubUser->save()) {
            return redirect()->route('admin.FanclubUser.index')->with('success', 'Thêm thành viên câu lạc bộ thành công!');
        } else {
            return back()->with('error', 'Có lỗi xảy ra trong quá trình lưu.');
        }
    }


    // Hiển thị form chỉnh sửa FanclubUser
    public function edit($id)
    {
        $fanclubuser = FanclubUser::find($id);
        if (!$fanclubuser) {
            return redirect()->route('admin.FanclubUser.index')->with('error', 'Không tìm thấy thành viên!');
        }

        // Lấy dữ liệu từ bảng users, fanclubs, và roles
        $users = User::all();
        $fanclubs = Fanclub::all();
        $roles = Role::all();

        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="' . route('admin.FanclubUser.index') . '">Danh sách thành viên</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sửa thành viên</li>';

        $active_menu = "fanclub_edit";

        return view('Fanclub::FanclubUser.edit', compact('breadcrumb', 'active_menu', 'users', 'fanclubs', 'roles', 'fanclubuser'));
    }


    // Cập nhật FanclubUser
    public function update(Request $request, $id)
    {
        $fanclubuser = FanclubUser::find($id);
        if (!$fanclubuser) {
            return redirect()->route('admin.FanclubUser.index')->with('error', 'Không tìm thấy thành viên!');
        }

        // Validate input
        $this->validate($request, [
            'user_id' => 'required|integer|exists:users,id',
            'fanclub_id' => 'required|integer|exists:fanclubs,id',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        // Cập nhật thông tin thành viên
        $fanclubuser->user_id = $request->user_id;
        $fanclubuser->fanclub_id = $request->fanclub_id;
        $fanclubuser->role_id = $request->role_id;

        if ($fanclubuser->save()) {
            return redirect()->route('admin.FanclubUser.index')->with('success', 'Cập nhật thành viên thành công!');
        } else {
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật thành viên.');
        }
    }


    // Xóa FanclubUser
    public function destroy($id)
    {
        $fanclubuser = FanclubUser::findOrFail($id);
        $fanclubuser->delete();

        return redirect()->route('admin.FanclubUser.index')->with('success', 'Tài nguyên đã được xóa!');
    }

}
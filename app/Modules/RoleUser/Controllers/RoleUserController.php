<?php

namespace App\Modules\RoleUser\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\RoleUser\Models\RoleUser;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    protected $pagesize;

    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        
    }

    public function index()
    {
        $func = "role_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $active_menu="role_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.roleuser.index').'">Quyền người dùng</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách quyền người dùng </li>';
        $role_user = RoleUser::orderBy('id','DESC')->paginate($this->pagesize);
        // categories
        return view('RoleUser::roleuser.index',compact('role_user','breadcrumb','active_menu'));
    }

    public function create()
    {
        $func = "addrole";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $data['active_menu']="role_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.roleuser.index').'">Quyền người dùng</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Thêm quyền người dùng </li>';
        return view('RoleUser::roleuser.create', $data);
    }

    public function store(Request $request)
    {

        $func = "addrole";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        
        //
        $this->validate($request,[
            'alias' => 'required|unique:role_user,alias',
            'title' => 'required',
            'type' => 'required|in:user,group',
            'status'=>'required|in:active,inactive',
        ]);

        $data = $request->all();

        $role = RoleUser::create($data);
        
        if($role){
            return redirect()->route('admin.roleuser.index')->with('success','Tạo quyền người dùng thành công!');
        }
        else
        {
            return back()->with('error','Có lỗi xãy ra!');
        } 
        
    }

    public function edit($id)
    {
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.roleuser.index').'">Quyền người dùng</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Sửa quyền người dùng </li>';
        $func = "role_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        
        $active_menu="role_list";

        $role = RoleUser::findOrFail($id);
        return view('RoleUser::roleuser.edit', compact('role','breadcrumb' , 'active_menu'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'alias' => 'required|unique:role_user,alias,' . $id,
            'title' => 'required',
            'type' => 'required|in:user,group',
            'status' => 'required|in:active,inactive',
        ]);

        $role = RoleUser::findOrFail($id);
        $role->update($request->all());

        return redirect()->route('admin.roleuser.index')->with('success', 'Cập nhật quyền người dùng thành công!');
    }

    public function destroy($id)
    {
        $func = "delete_role";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $role = RoleUser::findOrFail($id);

        $role->delete();

        return redirect()->route('admin.roleuser.index')->with('success', 'Đã xóa quyền người dùng thành công!');
    }
}
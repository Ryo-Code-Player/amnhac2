<?php

namespace App\Modules\Group\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Modules\Group\Models\GroupType;
use App\Modules\Group\Models\Group;

class GroupTypeController extends Controller
{
    protected $pagesize;
    public function index()
    {
        $func = "gtype_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="gtype_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Loại nhóm </li>';
        $gtype=GroupType::orderBy('id')->paginate($this->pagesize);
        return view('Group::grouptype.index',compact('gtype','breadcrumb','active_menu'));
    }

    public function GroupTypeSearch(Request $request)
    {
        $func = "gtype_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="gtype_list";
            $searchdata =$request->datasearch;
            $grouptype = GroupType::where('nametitle','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)
            ->withQueryString();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.grouptype.index').'">Loại nhóm</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            
            return view('Group::grouptype.search',compact('grouptype','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('admin.grouptype.index')->with('success','Không có thông tin tìm kiếm!');
        }
    }

    public function create()
    {
        //
        $func = "gtype_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="gtype_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.grouptype.index').'">Các loại nhóm</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Tạo mô tả nhóm </li>';
        return view('Group::grouptype.create',compact('breadcrumb','active_menu'));
    }

    public function store(Request $request)
    {
        $func = "gtype_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        // return $request->all();
        $this->validate($request,[
            'nametitle'=>'string|required',
            'description'=>'string|nullable',
        ]);
        $data = $request->all();
        $status = GroupType::create($data);
        if($status){
            return redirect()->route('admin.grouptype.index')->with('success','Tạo mô tả nhóm thành công!');
        }
        else
        {
            return back()->with('error','Có lỗi xãy ra!');
        }    
    }

    public function edit(string $id)
    {
        //
        $func = "gtype_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $grouptype = GroupType::find($id);
        if($grouptype)
        {
            $active_menu="gtype_edit";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.grouptype.index').'">Loại Nhóm</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Điều chỉnh mô tả nhóm </li>';
            return view('Group::grouptype.edit',compact('breadcrumb','grouptype','active_menu'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }

    public function update(Request $request, string $id)
    {
        $func = "gtype_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $grouptype = GroupType::find($id);
        if($grouptype)
        {
            $this->validate($request,[
                'nametitle'=>'string|required',
                'description'=>'string|nullable',
            ]);
            $data = $request->all();
            $status = $grouptype->fill($data)->save();
            if($status){
                return redirect()->route('admin.grouptype.index')->with('success','Cập nhật thành công');
            }
            else
            {
                return back()->with('error','Something went wrong!');
            }    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
      
    }

    public function destroy(string $id)
    {
        //
        $func = "gtype_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $grouptype = GroupType::find($id);
        if($grouptype)
        {
            $users = Group::where('id',$grouptype->id)->get();
            if(count($users) > 0)
            {
                return back()->with('error','Có nhóm sử dụng mô tả này! Không thể xóa!');
            }
           
            $status = $grouptype->delete();
            if($status){
                return redirect()->route('admin.grouptype.index')->with('success','Xóa thành công!');
            }
            else
            {
                return back()->with('error','Có lỗi xãy ra!');
            }    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
}
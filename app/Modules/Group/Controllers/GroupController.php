<?php

namespace App\Modules\Group\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Modules\Group\Models\GroupType;
use App\Modules\Group\Models\Group;
use App\Modules\Group\Models\GroupMember;
use App\Models\User;
use App\Modules\Blog\Models\Blog;
use App\Modules\Resource\Models\Resource;

class GroupController extends Controller
{
    protected $pagesize;
    public function index()
    {
        $func = "group_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="group_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Nhóm </li>';
        $group=Group::with('type')->orderBy('id','DESC')->paginate($this->pagesize);
        // $grouptype=GroupType::orderBy('id','DESC')->paginate($this->pagesize);
        return view('Group::group.index',compact('group','breadcrumb','active_menu'));
    }

    public function GroupSearch(Request $request)
    {
        $func = "group_list";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $active_menu = "group_list";
        $searchdata = $request->datasearch;

        if ($searchdata) {
            // Tìm kiếm nhóm theo tiêu đề hoặc alias
            $group = Group::where('title', 'LIKE', '%' . $searchdata . '%')
                ->paginate($this->pagesize)
                ->withQueryString();

            $breadcrumb = '
                <li class="breadcrumb-item"><a href="#">/</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="' . route('admin.group.index') . '">Nhóm</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tìm kiếm</li>';

            return view('Group::group.search', compact('group', 'breadcrumb', 'searchdata', 'active_menu'));
        } else {
            return redirect()->route('admin.group.index')->with('success', 'Không có thông tin tìm kiếm!');
        }
    }

    public function create()
    {
        //
        $func = "group_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="group_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.group.index').'">Danh sách nhóm</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Tạo nhóm </li>';
        
        $grouptype=GroupType::orderBy('id','DESC')->paginate($this->pagesize);
        return view('Group::group.create',compact('grouptype','breadcrumb','active_menu'));
    }

    public function store(Request $request)
    {
        $func = "group_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        // return $request->all();
        $this->validate($request,[
            'type_id' => 'required|exists:group_types,id', 
            'title' => 'required|string|max:255',
            'photo' => 'nullable|string',
            'description' => 'nullable|string',
            'is_private' => 'required|in:public, private',
            'status' => 'required|in:active,inactive'
        ]);
        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        if ($request->photo == null)
            $data['photo'] = asset('backend/images/profile-6.jpg');
        if ($request->photo != null) {
            $photos = explode(',', $data['photo']);
        if (count($photos) > 0)
            $data['photo'] = $photos[0];
        }

        $status = Group::create($data);
        if($status){
            return redirect()->route('admin.group.index')->with('success','Tạo mô tả nhóm thành công!');
        }
        else
        {
            return back()->with('error','Có lỗi xãy ra!');
        }    
    }

    public function edit(string $id)
    {
        //
        $func = "group_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $group = Group::find($id);
        
        $grouptype=GroupType::orderBy('id','DESC')->paginate($this->pagesize);
        if($group)
        {
            $active_menu="group_edit";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.group.index').'">Danh sách nhóm</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Điều chỉnh nhóm </li>';
            return view('Group::group.edit',compact('breadcrumb','group', 'grouptype','active_menu'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }

    public function update(Request $request, string $id)
    {
        $func = "group_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $group = Group::find($id);
        if($group)
        {
            $this->validate($request,[
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_private' => 'required|in:public,private',
                'status' => 'required|in:active,inactive',
                'type_id' => 'required|exists:group_types,id',
            ]);
            $data = $request->all();
            $data['slug'] = Str::slug($request->title);
            if ($request->photo == null)
                $data['photo'] = asset('backend/images/profile-6.jpg');
            if ($request->photo != null) {
                $photos = explode(',', $data['photo']);
            if (count($photos) > 0)
                $data['photo'] = $photos[0];
            }
            $status = $group->fill($data)->save();
            if($status){
                return redirect()->route('admin.group.index')->with('success','Cập nhật thành công');
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
        $func = "group_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $group = Group::find($id);
        if($group)
        {
            $status = $group->delete();
            if($status){
                return redirect()->route('admin.group.index')->with('success','Xóa thành công!');
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

    public function show(string $id)
    {
        $func = "group_show";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $group = Group::find($id);
        $user = User::orderBy('id','DESC')->paginate($this->pagesize);
        $blog = Blog::orderBy('id','DESC')->paginate($this->pagesize);
        $resource = Resource::orderBy('id','DESC')->paginate($this->pagesize);
        $members = GroupMember::where('group_id', $id)->with('user')->get();
        if($group)
        {
            $active_menu="group_add";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.group.index').'">Danh sách nhóm</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Thành viên nhóm </li>';
            return view('Group::group.show',compact('breadcrumb', 'group','members', 'user', 'blog', 'resource','active_menu'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }

    public function add(string $id, string $link)
    {
        $func = "group_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $group = Group::find($id);
        $user = User::orderBy('id','DESC')->paginate($this->pagesize);
        $blog = Blog::orderBy('id','DESC')->paginate($this->pagesize);
        $resource = Resource::orderBy('id','DESC')->paginate($this->pagesize);
        $linkadd = $link;
        if($group)
        {
            $active_menu="group_add";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.group.index').'">Danh sách nhóm</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Các thành phần </li>';
            return view('Group::group.add',compact('breadcrumb','group', 'user', 'blog', 'resource','active_menu', 'linkadd'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }

    public function addgroupuser(Request $request, string $groupid, string $id, string $link)
    {
        $func = "group_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $linkadd = $link;
        $group = Group::find($groupid);
        if($group){
            if($linkadd == 'user'){
                $user = User::findOrFail($id);
                $group->users()->attach($user, ['role_id' => 11]);
            }
            elseif($linkadd == 'blog'){
                $blog = Blog::findOrFail($id);
                $group->blogs()->attach($blog);
            }
            else{
                $resource = Resource::findOrFail($id);
                $group->resources()->attach($resource);
            }
        }
        else
        {
            return redirect()->route('admin.group.index')->with('error', 'Nhóm không tồn tại');
        }

        $validLinks = ['user', 'blog', 'resource'];
        if (!in_array($linkadd, $validLinks)) {
            return redirect()->route('admin.group.index')->with('error', 'Loại không hợp lệ');
        }

        return redirect()->route('admin.group.index')->with('success','Cập nhật thành công');  
    }

    public function remove(Request $request, string $groupid, string $id, string $link)
    {
        $func = "group_remove";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $linkadd = $link;
        $group = Group::findOrFail($groupid);
        // if($linkadd == 'user'){
        $user = User::findOrFail($id);
        $group->users()->detach($user);
        // }
        // elseif($linkadd === 'blog'){
        //     $blog = Blog::findOrFail($id);
        //     $group->blogs()->attach($blog);
        // }
        // elseif($linkadd == 'resource'){
        //     $resource = Resource::findOrFail($id);
        //     $group->resources()->attach($resource);
        // }
        // else{
        //     return redirect()->route('admin.group.index')->with('error', 'Loại không hợp lệ');
        // }

        return redirect()->route('admin.group.index')->with('success','Cập nhật thành công');  
    }
}
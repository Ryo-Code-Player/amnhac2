<?php

namespace App\Modules\interact\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\interact\Models\UserPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserPageController extends Controller
{
    protected $pagesize;
    public function index()
    {
        $func = "interact_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="interact_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Tương tác </li>';
        $userpage=UserPage::orderBy('id','DESC')->paginate($this->pagesize);
        return view('interact::userpage.index',compact('userpage','breadcrumb','active_menu'));
    }

    public function create()
    {
        //
        $func = "interact_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="interact_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.userpage.index').'">Danh sách trang cá nhân</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Tạo trang cá nhân </li>';
        
        $userpage=UserPage::orderBy('id','DESC')->paginate($this->pagesize);
        return view('interact::userpage.create',compact('userpage','breadcrumb','active_menu'));
    }

    public function store(Request $request)
    {
        $func = "interact_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        // return $request->all();
        $this->validate($request,[
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
        ]);
        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        $status = UserPage::create($data);
        if($status){
            return redirect()->route('admin.userpage.index')->with('success','Tạo trang cá nhân thành công!');
        }
        else
        {
            return back()->with('error','Có lỗi xãy ra!');
        }    
    }

    public function edit(string $id)
    {
        //
        $func = "interact_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $userpage = UserPage::find($id);
        if($userpage)
        {
            $active_menu="group_edit";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.userpage.index').'">Danh sách trang cá nhân</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Điều chỉnh trang cá nhân </li>';
            return view('interact::userpage.edit',compact('breadcrumb','userpage','active_menu'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }

    public function update(Request $request, string $id)
    {
        $func = "interact_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $userpage = UserPage::find($id);
        if($userpage)
        {
            $this->validate($request,[
                'title' => 'required|string|max:255',
                'summary' => 'nullable|string',
            ]);
            $data = $request->all();
            $data['slug'] = Str::slug($request->title);
            $status = $userpage->fill($data)->save();
            if($status){
                return redirect()->route('admin.userpage.index')->with('success','Cập nhật thành công');
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
        $func = "interact_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $userpage = UserPage::find($id);
        if($userpage)
        {
            $status = $userpage->delete();
            if($status){
                return redirect()->route('admin.userpage.index')->with('success','Xóa thành công!');
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
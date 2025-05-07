<?php

namespace App\Modules\Fanclub\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Fanclub\Models\FanclubItem;
use App\Modules\Resource\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FanclubItemController extends Controller
{
    protected $pagesize;
    public function index()
    {
        // dd(1);
        $func = "fanclub_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="fanclub_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách tài nguyên </li>';
        // $fanclubitem = FanclubItem::orderBy('id','DESC')->paginate($this->pagesize);
        $fanclubitem=FanclubItem::with('resource')->orderBy('id','DESC')->paginate($this->pagesize);
        return view('Fanclub::FanclubItem.index',compact('fanclubitem','breadcrumb','active_menu'));
    }

    // Hiển thị form tạo mới FanclubItem
    public function create()
    {
        $func = "fanclub_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="fanclub_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.FanclubItem.index').'">Danh sách tài nguyên</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Tạo tài nguyên </li>';
        
        $fanclubitem=FanclubItem::orderBy('id','DESC')->paginate($this->pagesize);
        $resource=Resource::orderBy('id','DESC')->paginate($this->pagesize);
        return view('Fanclub::FanclubItem.create',compact('fanclubitem', 'resource','breadcrumb','active_menu'));
    }

    // Lưu FanclubItem mới
    public function store(Request $request)
    {
        $func = "fanclub_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        // Validate input
        $this->validate($request, [
            'resource_code' => 'required|string|max:255',
            'resource_id' => 'required|integer',
        ]);
        $data = $request->all();
        $status = FanclubItem::create($data);

        if ($status) {
            return redirect()->route('admin.FanclubItem.index')->with('success', 'Tạo tài nguyên câu lạc bộ thành công!');
        } else {
            return back()->with('error', 'Có lỗi xãy ra!');
        }
    }

    // Hiển thị form chỉnh sửa FanclubItem
    public function edit($id)
    {
        $func = "fanclub_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $fanclubitem = FanclubItem::find($id);
        $resource=Resource::orderBy('id','DESC')->paginate($this->pagesize);
        if($fanclubitem)
        {
            $active_menu="fanclub_edit";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.FanclubItem.index').'">Danh sách tài nguyên</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Điều chỉnh tài nguyên </li>';
            return view('Fanclub::FanclubItem.edit',compact('breadcrumb', 'resource','fanclubitem','active_menu'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }

    // Cập nhật FanclubItem
    public function update(Request $request, $id)
    {
        $func = "fanclub_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $fanclubitem = FanclubItem::find($id);
        if($fanclubitem)
        {
            $this->validate($request,[
                'resource_id' => 'required|integer',
                'resource_code' => 'required|string|max:255',
            ]);
            $data = $request->all();
            $status = $fanclubitem->fill($data)->save();
            if($status){
                return redirect()->route('admin.FanclubItem.index')->with('success','Cập nhật thành công');
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

    // Xóa FanclubItem
    public function destroy($id)
    {
        $fanclubitem = FanclubItem::findOrFail($id);
        $fanclubitem->delete();

        return redirect()->route('admin.FanclubItem.index')->with('success', 'Tài nguyên đã được xóa!');
    }

}
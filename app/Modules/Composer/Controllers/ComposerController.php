<?php

namespace App\Modules\Composer\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Modules\Composer\Models\Composer; // Mô hình Composer
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ComposerController extends Controller
{
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','10');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $active_menu = "composer_management";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách nhạc sĩ</li>';

        $composers = Composer::paginate(10);

        return view('Composer::composer.index', compact('composers', 'active_menu', 'breadcrumb'));
    }

    public function create()
    {
        session(['active_menu' => 'composer_add']);
        $active_menu = "composer_management";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.composer.index') . '">Danh sách nhạc sĩ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm nhạc sĩ</li>';

        return view('Composer::composer.create', compact('active_menu', 'breadcrumb'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'photo' => 'string|nullable',
            'status' => 'required|string|in:active,inactive',
        ]);

        $validatedData['fullname'] = strip_tags($validatedData['fullname']);
        $validatedData['summary'] = strip_tags($validatedData['summary']);
        $validatedData['content'] = strip_tags($validatedData['content']);

        $composer = new Composer($validatedData);
        $composer->slug = $this->createSlug($composer->fullname);
        $composer->user_id = Auth::id();
        $composer->photo = $validatedData['photo'] ?? asset('backend/images/profile-6.jpg');

        $composer->save();

        return redirect()->route('admin.composer.index')->with('success', 'Nhạc sĩ đã được thêm thành công.');
    }

    public function edit($id)
    {
        $active_menu = "composer_management";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.composer.index') . '">Danh sách nhạc sĩ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Điều chỉnh nhạc sĩ</li>';

        $composer = Composer::findOrFail($id);

        return view('Composer::composer.edit', compact('composer', 'active_menu', 'breadcrumb'));
    }

    public function update(Request $request, $id)
    {
        $composer = Composer::findOrFail($id);

        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'photo' => 'string|nullable',
            'status' => 'required|string|in:active,inactive',
        ]);

        if ($composer->fullname !== $request->fullname) {
            $composer->slug = $this->createSlug($request->fullname);
        }

        $validatedData['photo'] = $validatedData['photo'] ?? asset('backend/images/profile-6.jpg');
        $composer->fill($validatedData);

        Log::info('Updating composer with data:', $validatedData);
        $composer->save();

        return redirect()->route('admin.composer.index')->with('success', 'Nhạc sĩ đã được cập nhật thành công.');
    }

    public function destroy(string $id)
    {
        $composer = Composer::find($id);

        if ($composer) {
            if ($composer->photo && $composer->photo !== 'backend/images/profile-6.jpg') {
                Storage::disk('public')->delete($composer->photo);
            }

            $composer->delete();
            return redirect()->route('admin.composer.index')->with('success', 'Xóa nhạc sĩ thành công!');
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu nhạc sĩ!');
        }
    }

    public function show($id)
    {
        $composer = Composer::findOrFail($id);
        $active_menu = 'composer_management';
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.composer.index') . '">Danh sách nhạc sĩ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chi Tiết Nhạc Sĩ</li>';

        return view('Composer::composer.show', compact('composer', 'active_menu', 'breadcrumb'));
    }

    protected function createSlug($title)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,gif|max:2048', // Kích thước tối đa 2MB
        ]);
    
        if ($request->file('file')) {
            $file = $request->file('file');
            $path = $file->store('public/avatar'); // Lưu vào storage/app/public/avatar
    
            // Lấy link công khai
            $link = Storage::url($path);
    
            return response()->json(['status' => true, 'link' => $link]);
        }
    
        return response()->json(['status' => false, 'message' => 'File không hợp lệ.']);
    }
    public function status(Request $request, $id)
{
    // Kiểm tra xem composer có tồn tại không
    $composer = Composer::find($id);
    if (!$composer) {
        return response()->json(['msg' => 'Không tìm thấy composer.'], 404);
    }

    // Cập nhật trạng thái
    $composer->status = $request->mode;
    $composer->save();

    // Trả về phản hồi thành công
    return response()->json(['msg' => 'Cập nhật thành công.']);
}

public function search(Request $request)
    {
        
        $func = "composer_management";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="composer_management";
            $searchdata =$request->datasearch;
            $composers = DB::table('composers')->where('fullname','LIKE','%'.$request->datasearch.'%')->orWhere('content','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
             <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.composer.index').'">Danh sách nhạc sĩ</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('Composer::composer.search', compact('composers', 'breadcrumb', 'searchdata', 'active_menu'));

        }
        else
        {
            return redirect()->route('admin.composer.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
}

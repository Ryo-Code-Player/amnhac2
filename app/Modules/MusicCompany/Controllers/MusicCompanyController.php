<?php

namespace App\Modules\MusicCompany\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Modules\MusicCompany\Models\MusicCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Modules\Resource\Models\Resource;



class MusicCompanyController extends Controller
{


    public function index()
{
    $active_menu = "musiccompany_list";
    $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách Công ty Âm nhạc</li>';
    
    $music_companies = MusicCompany::with('resources')->paginate(10);
    $allResources = Resource::all();

    return view('MusicCompany::musiccompany.index', compact('music_companies', 'active_menu', 'allResources', 'breadcrumb'));
}


public function create()
{
    $active_menu = "musiccompany_list"; // Xác định trạng thái menu hiện tại
    $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm Công Ty Âm Nhạc</li>';
    $allResources = Resource::all(); 

    // Truyền biến $breadcrumb vào view nếu bạn cần sử dụng nó
    return view('MusicCompany::musiccompany.create', compact('active_menu', 'allResources', 'breadcrumb'));
}


public function store(Request $request)
{
    
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'address' => 'nullable|string',
        'photo'=>'string|nullable',
        'summary' => 'nullable|string',
        'content' => 'nullable|string',
        'tags' => 'nullable|string',
        'status' => 'required|string|in:active,inactive',
        'phone' => 'nullable|string',
        'email' => 'nullable|email',
    ]);

    // Remove HTML tags from title, summary, and content
    $validatedData['title'] = strip_tags($validatedData['title']);
    $validatedData['summary'] = strip_tags($validatedData['summary']);
    $validatedData['content'] = strip_tags($validatedData['content']);

    // Tạo một đối tượng MusicCompany
    $musicCompany = new MusicCompany($validatedData);

    // Tạo slug tự động
    $slug = $this->createSlug($musicCompany->title);
    $musicCompany->slug = $slug;

    // Thiết lập user_id từ người dùng hiện tại
    $musicCompany->user_id = Auth::id();

  // Gán ảnh mặc định nếu không có
  $data['photo'] = $data['photo'] ?? asset('backend/images/profile-6.jpg');

    // Lưu vào cơ sở dữ liệu
    $musicCompany->save();

    // Xử lý trường resources
    if ($request->has('resources')) {
        $resourceIds = array_map('intval', $request->resources);
        $musicCompany->resources()->attach($resourceIds);
    }

    // Chuyển hướng về danh sách công ty âm nhạc với thông báo thành công
    return redirect()->route('admin.musiccompany.index')->with('success', 'Công ty âm nhạc đã được thêm thành công.');
}
    
    

public function edit($id)
{
    $active_menu = "musiccompany_list"; // Xác định trạng thái menu hiện tại
    $breadcrumb = '
        <li class="breadcrumb-item"><a href="' . route('admin.musiccompany.index') . '">Danh sách công ty âm nhạc</a></li>
        <li class="breadcrumb-item active" aria-current="page">Điều chỉnh công ty âm nhạc</li>';
    
    // Tìm công ty âm nhạc cùng với tài nguyên
    $musicCompany = MusicCompany::with('resources')->findOrFail($id);
    
    // Lấy tất cả tài nguyên
    $allResources = Resource::all();
    
    // Trả về view với dữ liệu cần thiết
    return view('MusicCompany::musiccompany.edit', compact('musicCompany', 'active_menu', 'allResources', 'breadcrumb'));
}


    public function update(Request $request, $id)
    {
        // Tìm công ty âm nhạc theo ID
        $musicCompany = MusicCompany::findOrFail($id);
    
        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'address' => 'nullable|string',
            'photo' => 'string|nullable',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'tags' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);
    
        // Cập nhật slug nếu tiêu đề đã thay đổi
        if ($musicCompany->title !== $request->title) {
            $musicCompany->slug = $this->createSlug($request->title);
        }
    
        // Xử lý trường photo
        $validatedData['photo'] = $validatedData['photo'] ?? asset('backend/images/profile-6.jpg');
    
        // Cập nhật các thuộc tính khác mà không bao gồm 'resources'
        $musicCompany->fill($validatedData);
    
        // Log validated data for debugging
        Log::info('Updating music company with data:', $validatedData);
    
        // Lưu lại bản ghi
        $musicCompany->save();
    
        // Xử lý trường resources
        if ($request->has('resources')) {
            $resourceIds = array_map('intval', $request->resources);
            $musicCompany->resources()->sync($resourceIds); // Cập nhật mối quan hệ
        }
    
        // Chuyển hướng với thông báo thành công
        return redirect()->route('admin.musiccompany.index')->with('success', 'Công ty âm nhạc đã được cập nhật thành công.');
    }
    

    public function destroy(string $id)
    {
        $func = "musiccompany_delete"; 
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $musicCompany = MusicCompany::find($id);

        if ($musicCompany) {
            // Xóa ảnh nếu có
            if ($musicCompany->photo && $musicCompany->photo !== 'backend/images/profile-6.jpg') {
                Storage::disk('public')->delete($musicCompany->photo);
            }

            // Xóa bản ghi công ty âm nhạc
            $status = $musicCompany->delete();
            
            if ($status) {
                return redirect()->route('admin.musiccompany.index')->with('success', 'Xóa công ty âm nhạc thành công!');
            } else {
                return back()->with('error', 'Có lỗi xảy ra khi xóa công ty âm nhạc!');
            }    
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu công ty âm nhạc!');
        }
    }

    public function show($id)
    {
        try {
            $company = MusicCompany::findOrFail($id);
            $active_menu = 'musiccompany';
            return view('MusicCompany::musiccompany.show', compact('company', 'active_menu'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
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
}

<?php

namespace App\Modules\Singer\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Modules\Singer\Models\Singer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Modules\Tag\Models\Tag;
use App\Modules\MusicType\Models\MusicType;
use App\Modules\MusicCompany\Models\MusicCompany;
use Illuminate\Support\Facades\DB; // Thêm dòng này
class SingerController extends Controller
{
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','10');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $active_menu = "singer_management";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách Ca sĩ</li>';
          // Lấy danh sách các công ty âm nhạc
    $musicCompanies = MusicCompany::all(); 
        $singers = Singer::paginate(10);
       
        return view('Singer::singer.index', compact('musicCompanies','singers', 'active_menu', 'breadcrumb'));
    }

    public function create()
    { 
         //Lấy danh sách thể loại âm nhạc
    $music_types = MusicType::all();
          // Lấy danh sách các công ty âm nhạc
    $musicCompanies = MusicCompany::all(); // Đảm bảo biến này được định nghĩa

        $tags = Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
        $active_menu = "singer_management";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.singer.index') . '">Danh sách Ca sĩ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm Ca sĩ</li>';

        return view('Singer::singer.create', compact( 'music_types', 'musicCompanies', 'active_menu', 'breadcrumb', 'tags'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'alias' => 'required|string|max:255',
            'slug' => 'nullable|string',
            'photo' => 'nullable|string',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'start_year' => 'nullable|integer',
            'status' => 'required|string|in:active,inactive',
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:music_companies,id', 
            'type_id' => 'required|exists:music_types,id', 
            'tags' => 'nullable|array', // Kiểm tra tags có phải là mảng không
            'tags.*' => 'exists:tags,id',  // Kiểm tra các tag có sẵn
            'new_tags' => 'nullable|string',  // Kiểm tra các tag nhập thêm
        ]);
     // Khởi tạo mảng tagsArray từ tags đã chọn
     $tagsArray = $request->tags ?? [];  // Nếu không có tags chọn, để mặc định là mảng rỗng

     // Xử lý tag mới nhập vào (nếu có)
     if ($request->new_tags) {
         $newTags = explode(',', $request->new_tags);  // Tách các tag mới từ dấu phẩy
 
         // Lọc và tạo các tag mới nếu chưa tồn tại
         foreach ($newTags as $newTag) {
             $newTag = trim($newTag);  // Loại bỏ khoảng trắng thừa
             if (!empty($newTag)) {
                 // Tạo tag mới nếu chưa tồn tại
                 $tag = Tag::firstOrCreate(['title' => $newTag]);
                 $tagsArray[] = $tag->id;  // Thêm ID của tag mới vào mảng tags
             }
         }
     }
            // Xử lý summary: giữ các thẻ <p> và thêm <p> cho các đoạn văn
            if ($validatedData['summary']) {
                // Đảm bảo thẻ <p> được bao quanh các đoạn văn
                $validatedData['summary'] = '<p>' . implode('</p><p>', explode("\n", $validatedData['summary'])) . '</p>';
            }

            // Xử lý content: giữ các thẻ <p> và thêm <p> cho các đoạn văn
            if ($validatedData['content']) {
                // Đảm bảo thẻ <p> được bao quanh các đoạn văn
                $validatedData['content'] = '<p>' . implode('</p><p>', explode("\n", $validatedData['content'])) . '</p>';
            }

        $validatedData['alias'] = strip_tags($validatedData['alias']);
        $validatedData['summary'] = strip_tags($validatedData['summary']);
        $validatedData['content'] = strip_tags($validatedData['content']);
        $validatedData['type_id'] = $request->input('type_id');
        $validatedData['slug'] = $this->createSlug($validatedData['alias']);
        
        $singer = new Singer($validatedData);
        $singer->user_id = Auth::id();
        $singer->tags = json_encode(value: $tagsArray);  // Lưu mảng tags dưới dạng JSON
        $singer->photo = $validatedData['photo'] ?? asset('backend/images/profile-6.jpg');
        $singer->save();
    

        
        return redirect()->route('admin.singer.index')->with('success', 'Ca sĩ đã được thêm thành công.');
    }
    

    public function edit($id)
    {
        // Lấy đối tượng ca sĩ cụ thể
        $singer = Singer::findOrFail($id);

        // Lấy tất cả tag có trạng thái 'active'
        $tags = Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
        
        $active_menu = "singer_management";
        
        // Breadcrumb cho trang chỉnh sửa
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.singer.index') . '">Danh sách Ca sĩ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Điều chỉnh Ca sĩ</li>';
            
        // Lấy tất cả công ty âm nhạc
        $musicCompanies = MusicCompany::all();
    
        // Lấy danh sách thể loại âm nhạc
        $music_types = MusicType::all();
        // Giải mã cột tags nếu có dữ liệu
        $attachedTags = json_decode($singer->tags, true) ?? []; // Mặc định là mảng rỗng nếu không có dữ liệu


        // Trả về view với tất cả biến đã chuẩn bị
        return view('Singer::singer.edit', compact('attachedTags','musicCompanies', 'singer', 'active_menu', 'breadcrumb',  'music_types', 'tags'));
    }
    
    
    

    public function update(Request $request, $id)
{
    $singer = Singer::findOrFail($id);

    $validatedData = $request->validate([
        'alias' => 'required|string|max:255',
        'slug' => 'nullable|string',
        'photo' => 'nullable|string',
        'summary' => 'nullable|string',
        'content' => 'nullable|string',
        'start_year' => 'nullable|integer',
        'status' => 'required|string|in:active,inactive',
        'user_id' => 'required|exists:users,id',
        'company_id' => 'required|exists:music_companies,id',
        'tags' => 'nullable|array', // Kiểm tra tags có phải là mảng không
        'tags.*' => 'exists:tags,id',  // Kiểm tra các tag có sẵn
    ]);

    // Khởi tạo mảng tagsArray từ tags đã chọn
    $tagsArray = $request->tags ?? [];  // Nếu không có tags chọn, để mặc định là mảng rỗng

    // Xử lý tag mới nhập vào (nếu có)
    if ($request->new_tags) {
        $newTags = explode(',', $request->new_tags);  // Tách các tag mới từ dấu phẩy

        // Lọc và tạo các tag mới nếu chưa tồn tại
        foreach ($newTags as $newTag) {
            $newTag = trim($newTag);  // Loại bỏ khoảng trắng thừa
            if (!empty($newTag)) {
                // Tạo tag mới nếu chưa tồn tại
                $tag = Tag::firstOrCreate(['title' => $newTag]);
                $tagsArray[] = $tag->id;  // Thêm ID của tag mới vào mảng tags
            }
        }
    }
// Loại bỏ thẻ <p> và các thẻ HTML khác
$validatedData['summary'] = strip_tags($validatedData['summary'], '<p>');

// Thay thế dấu xuống dòng (\n) bằng thẻ <br> 
$validatedData['summary'] = nl2br($validatedData['summary']);

// Làm tương tự cho content
$validatedData['content'] = strip_tags($validatedData['content'], '<p>');
$validatedData['content'] = nl2br($validatedData['content']);

    if ($singer->alias !== $request->alias) {
        $validatedData['slug'] = $this->createSlug($request->alias);
    }

    
    $validatedData['type_id'] = $request->input('type_id');
    $validatedData['photo'] = $validatedData['photo'] ?? asset('backend/images/profile-6.jpg');
    $singer->tags = json_encode($tagsArray);  // Lưu mảng tags dưới dạng JSON
    $singer->fill($validatedData);
    Log::info('Updating singer with data:', $validatedData);
    $singer->save();

   

    return redirect()->route('admin.singer.index')->with('success', 'Ca sĩ đã được cập nhật thành công.');
}

    public function destroy($id)
    {
        $singer = Singer::find($id);

        if ($singer) {
            if ($singer->photo && $singer->photo !== 'backend/images/profile-6.jpg') {
                Storage::disk('public')->delete($singer->photo);
            }

            // Kiểm tra và xóa các tag liên kết nếu có
            if ($singer->tags) {
                $tagsArray = json_decode($singer->tags, true);  // Chuyển tags thành mảng
    
                if (is_array($tagsArray)) {
                    foreach ($tagsArray as $tagId) {
                        // Kiểm tra xem tag có còn được sử dụng bởi bất kỳ MusicCompany nào khác không
                        $isTagInUse = MusicCompany::where('tags', 'like', '%"'.$tagId.'"%')->where('id', '!=', $singer->id)->exists();
    
                        // Nếu tag không còn được sử dụng, xóa nó khỏi bảng tags
                        if (!$isTagInUse) {
                            Tag::where('id', $tagId)->delete();
                        }
                    }
                }
            }
            $status = $singer->delete();

            if ($status) {
                return redirect()->route('admin.singer.index')->with('success', 'Xóa Ca sĩ thành công!');
            } else {
                return back()->with('error', 'Có lỗi xảy ra khi xóa Ca sĩ!');
            }
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu Ca sĩ!');
        }
    }

    public function show($id)
    {
        $tags = Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
        $active_menu = "singer_list";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.singer.index') . '">Danh sách Ca sĩ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chi Tiết Ca sĩ</li>';

        try {
            $singer = Singer::findOrFail($id);
            return view('Singer::singer.show', compact('singer', 'active_menu', 'breadcrumb', 'tags'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    protected function createSlug($alias)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $alias)));
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,gif|max:2048',
        ]);

        if ($request->file('file')) {
            $file = $request->file('file');
            $path = $file->store('public/avatar');
            $link = Storage::url($path);

            return response()->json(['status' => true, 'link' => $link]);
        }

        return response()->json(['status' => false, 'message' => 'File không hợp lệ.']);
    }

    public function status(Request $request, $id)
{
    // Kiểm tra xem ca sĩ có tồn tại không
    $singer = Singer::find($id);
    if (!$singer) {
        return response()->json(['msg' => 'Không tìm thấy ca sĩ.'], 404);
    }

    // Cập nhật trạng thái
    $singer->status = $request->mode;
    $singer->save();

    // Trả về phản hồi thành công
    return response()->json(['msg' => 'Cập nhật thành công.']);
}


public function search(Request $request)
    {
        
        $func = "singer_management";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="singer_management";
            $searchdata =$request->datasearch;
            $singers = DB::table('singers')->where('alias','LIKE','%'.$request->datasearch.'%')->orWhere('content','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
             <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.singer.index').'">Danh sách ca sĩ</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('Singer::singer.search', compact('singers', 'breadcrumb', 'searchdata', 'active_menu'));

        }
        else
        {
            return redirect()->route('admin.singer.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }

}

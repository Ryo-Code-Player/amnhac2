<?php

namespace App\Modules\MusicCompany\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Modules\MusicCompany\Models\MusicCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Modules\Resource\Models\Resource;
use App\Modules\Tag\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MusicCompanyController extends Controller
{
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','10');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $active_menu = "musiccompany_management";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách Công ty Âm nhạc</li>';
        
        $music_companies = MusicCompany::with('resources')->paginate(10);
        $allResources = Resource::all();

        return view('MusicCompany::musiccompany.index', compact('music_companies', 'active_menu', 'allResources', 'breadcrumb'));
    }

    public function create()
    {
        
        $tags = Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
        $active_menu = "musiccompany_management";
        $breadcrumb = '
             <li class="breadcrumb-item"><a href="' . route('admin.musiccompany.index') . '">Danh sách công ty âm nhạc</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm Công Ty Âm Nhạc</li>';
            $resources = Resource::all();
        return view('MusicCompany::musiccompany.create', compact('resources','active_menu', 'breadcrumb', 'tags'));
    }
    public function store(Request $request)
    {
        // Validate input
         $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'address' => 'nullable|string',
        'photo' => 'nullable|string',
        'summary' => 'nullable|string',
        'content' => 'nullable|string',
        'tags' => 'nullable|array', // Kiểm tra tags có phải là mảng không
        'tags.*' => 'exists:tags,id',  // Kiểm tra các tag có sẵn
        'new_tags' => 'nullable|string',  // Kiểm tra các tag nhập thêm
        'status' => 'required|in:active,inactive',  // Trạng thái là bắt buộc
        'phone' => 'required|string|unique:music_companies,phone',
        'email' => 'required|email|unique:music_companies,email',
        'resources' => 'nullable|array', // Validate resources as an array
    ]);
    
        // Khởi tạo mảng tagsArray từ tags đã chọn
        $tagsArray = $request->tags ?? [];
    
        // Xử lý tag mới nhập vào (nếu có)
        if ($request->new_tags) {
            $newTags = explode(',', $request->new_tags);
    
            foreach ($newTags as $newTag) {
                $newTag = trim($newTag);
                if (!empty($newTag)) {
                    $tag = Tag::firstOrCreate(['title' => $newTag]);
                    $tagsArray[] = $tag->id;
                }
            }
        }
    
        // Làm tương tự cho summary và content
        $validatedData['summary'] = strip_tags($validatedData['summary'], '<p>');
        $validatedData['summary'] = nl2br($validatedData['summary']);
    
        $validatedData['content'] = strip_tags($validatedData['content'], '<p>');
        $validatedData['content'] = nl2br($validatedData['content']);
  	 // Tạo mới công ty âm nhạc và lưu vào cơ sở dữ liệu
    	$musicCompany = new MusicCompany($validatedData);
    	$musicCompany->slug = $this->createSlug($musicCompany->title);
    	$musicCompany->user_id = Auth::id();
    	$musicCompany->status = $validatedData['status'];  // Thiết lập trạng thái cho công ty
    	$musicCompany->tags = json_encode($tagsArray);  // Lưu mảng tags dưới dạng JSON
      
    	$musicCompany->save();
        $tagsArray = json_decode($musicCompany->tags, true); // Giải mã JSON để lấy mảng
        $musicCompanyId = $musicCompany->id;
    
        // Mảng lưu trữ các tài nguyên
        $resourcesArray = [];
    
        // Xử lý tài nguyên và lưu vào bảng resources
        if ($request->has('resources') && is_array($request->resources)) {
            foreach ($request->resources as $resource) {
                if ($resource instanceof \Illuminate\Http\UploadedFile) {
                    // Nếu là file tải lên
                    $resourcePath = $resource->store('uploads/resources', 'public');
                    $resourceUrl = Storage::url($resourcePath);
                    $resourceUrl = Str::replaceFirst('http://localhost', '', $resourceUrl);
    
                    $slug = Str::slug($musicCompany->title) . '-' . Str::random(6);
                    $fileType = $resource->getMimeType();
                    $linkCode = '';
                    $typeCode = '';
    
                    // Xử lý các loại MIME type
                    if (strpos($fileType, 'image') !== false) {
                        $linkCode = 'image';
                        $typeCode = 'image';
                    } elseif (strpos($fileType, 'audio') !== false) {
                        $linkCode = 'audio';
                        $typeCode = 'audio';
                    } elseif (strpos($fileType, 'video') !== false) {
                        $linkCode = 'video';
                        $typeCode = 'video';
                    } elseif (strpos($fileType, 'pdf') !== false) {
                        $linkCode = 'document';
                        $typeCode = 'document';
                    }
                    $tagsArray = json_decode($musicCompany->tags, true); // Giải mã JSON để lấy mảng
                    // Lưu tài nguyên vào bảng resources
                    $resourceRecord = Resource::create([    
                         'company_id' => $musicCompanyId,
                        'title' => $musicCompany->title,
                        'slug' => $slug,
                        'url' => $resourceUrl,
                        'file_type' => $fileType,
                        'type_code' => $typeCode,
                        'tags' => !empty($tagsArray) ? json_encode($tagsArray) : null, // Gán tags từ musicCompany
                        'file_name' => $resource->getClientOriginalName(),
                        'file_size' => $resource->getSize(),
                        'code' => 'musiccompany',
                        'link_code' => $linkCode,
                    ]);
    
                    $resourcesArray[] = [
                        'company_id' => $musicCompanyId,
                        'resource_id' => $resourceRecord->id,
                    ];
                }
            }
        }
    
        // Lưu mảng tài nguyên vào MusicCompany
        if (!empty($resourcesArray)) {
            $musicCompany->resources = json_encode($resourcesArray);
            $musicCompany->save();
        }
    
        return redirect()->route('admin.musiccompany.index')->with('success', 'Music company đã được thêm thành công.');
    }
    

public function edit($id)
{
    // Lấy danh sách các thẻ (tags) có trạng thái "active"
    $tags = Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
    
    // Thiết lập menu và breadcrumb
    $active_menu = "musiccompany_management";
    $breadcrumb = '
        <li class="breadcrumb-item"><a href="' . route('admin.musiccompany.index') . '">Danh sách công ty âm nhạc</a></li>
        <li class="breadcrumb-item active" aria-current="page">Điều chỉnh công ty âm nhạc</li>';

    // Lấy thông tin công ty âm nhạc
    $musicCompany = MusicCompany::findOrFail($id);

    // Giải mã cột resources nếu có dữ liệu
    $resourcesArray = is_string($musicCompany->resources) ? json_decode($musicCompany->resources, true) : $musicCompany->resources;
    
    // Lấy tất cả các resource_id từ mảng resources đã gắn vào công ty âm nhạc
    $resourceIds = array_column($resourcesArray, 'resource_id');
    
    // Nếu có resource_id, lấy danh sách tài nguyên đã gắn cho công ty âm nhạc
    $resources = Resource::whereIn('id', $resourceIds)->get();
    
    // Lấy danh sách tài nguyên có thể thêm vào (tức là chưa được gắn)
    $availableResources = Resource::whereNotIn('id', $resourceIds)->get();
    
    // Giải mã cột tags nếu có dữ liệu
    $attachedTags = json_decode($musicCompany->tags, true) ?? []; // Mặc định là mảng rỗng nếu không có dữ liệu


    // Lấy tất cả các tài nguyên (resources) có thể gán vào công ty âm nhạc
    $allResources = Resource::all(); // Lấy tất cả tài nguyên, có thể thêm điều kiện lọc nếu cần
    
    // Trả về view chỉnh sửa với các thông tin cần thiết
    return view('MusicCompany::musiccompany.edit', compact(
        'musicCompany', 'resources', 'availableResources', 'tags', 'attachedTags', 'allResources', 'active_menu', 'breadcrumb'
    ));
}


public function update(Request $request, $id)
{
    // Validate input
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'address' => 'nullable|string',
        'photo' => 'nullable|string',
        'summary' => 'nullable|string',
        'content' => 'nullable|string',
        'tags' => 'nullable|array',
        'tags.*' => 'exists:tags,id',
        'new_tags' => 'nullable|string',
        'status' => 'required|string|in:active,inactive',
        'resources' => 'nullable|array',
    ]);

    // Khởi tạo mảng tagsArray từ tags đã chọn
    $tagsArray = $request->tags ?? [];

    // Xử lý tag mới nhập vào (nếu có)
    if ($request->new_tags) {
        $newTags = explode(',', $request->new_tags);
        foreach ($newTags as $newTag) {
            $newTag = trim($newTag);
            if (!empty($newTag)) {
                $tag = Tag::firstOrCreate(['title' => $newTag]);
                $tagsArray[] = $tag->id;
            }
        }
    }

    // Find the MusicCompany to update by ID
    $musicCompany = MusicCompany::findOrFail($id);
    $currentResources = $musicCompany->resources ? json_decode($musicCompany->resources, true) : [];

    // Handle image upload (if present)
    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('photos', 'public');
        $photoUrl = Storage::url($photoPath);
        $photoUrl = Str::replaceFirst('http://localhost', '', $photoUrl);
        $validatedData['photo'] = $photoUrl;
    }

    // Remove unwanted HTML tags
    $validatedData['summary'] = strip_tags($validatedData['summary'], '<p>');
    $validatedData['summary'] = nl2br($validatedData['summary']);

    $validatedData['content'] = strip_tags($validatedData['content'], '<p>');
    $validatedData['content'] = nl2br($validatedData['content']);

    // Update MusicCompany data, without changing the ID
    $musicCompany->fill($validatedData);
    $musicCompany->slug = $this->createSlug($musicCompany->title);
    $musicCompany->user_id = Auth::id();
    $musicCompany->tags = json_encode($tagsArray);
    $musicCompany->save();

    // Handle resources and update them
    if ($request->has('resources') && is_array($request->resources)) {
        $resourcesArray = [];
        foreach ($request->resources as $resource) {
            if ($resource instanceof \Illuminate\Http\UploadedFile) {
                $resourcePath = $resource->store('uploads/resources', 'public');
                $resourceUrl = Storage::url($resourcePath);
                $resourceUrl = Str::replaceFirst('http://localhost', '', $resourceUrl);

                $slug = Str::slug($musicCompany->title) . '-' . Str::random(6);
                $fileType = $resource->getMimeType();
                $typeCode = '';
                $linkCode = '';
                
                if (strpos($fileType, 'image') !== false) {
                    $typeCode = 'image';
                    $linkCode = 'image';
                } elseif (strpos($fileType, 'audio') !== false) {
                    $typeCode = 'audio';
                    $linkCode = 'audio';
                } elseif (strpos($fileType, 'video') !== false) {
                    $typeCode = 'video';
                    $linkCode = 'video';
                } elseif (
                    strpos($fileType, 'pdf') !== false || 
                    strpos($fileType, 'doc') !== false || 
                    strpos($fileType, 'txt') !== false || 
                    strpos($fileType, 'rar') !== false || 
                    strpos($fileType, 'zip') !== false || 
                    strpos($fileType, 'xls') !== false || 
                    strpos($fileType, 'ppt') !== false
                ) {
                    $typeCode = 'document';
                    $linkCode = 'document';
                }

                $resourceRecord = Resource::create([
                    'company_id' => $musicCompany->id,
                    'title' => $musicCompany->title,
                    'slug' => $slug,
                    'url' => $resourceUrl,
                    'file_type' => $fileType,
                    'link_code' => $linkCode,
                    'tags' => $tag,
                    'code' => 'musiccompany',
                    'type_code' => $typeCode,
                    'file_name' => $resource->getClientOriginalName(),
                    'file_size' => $resource->getSize(),
                ]);

                $resourcesArray[] = [
                    'company_id' => $musicCompany->id,
                    'resource_id' => $resourceRecord->id,
                ];
            }
        }

        $combinedResources = array_merge($currentResources, $resourcesArray);
        $musicCompany->resources = json_encode($combinedResources);
        $musicCompany->save();
    } else {
        $musicCompany->resources = json_encode($currentResources);
        $musicCompany->save();
    }

    return redirect()->route('admin.musiccompany.index')->with('success', 'Công ty âm nhạc đã được cập nhật thành công.');
}


  
    public function destroy(string $id)
    {
        $musicCompany = MusicCompany::find($id);
    
        if ($musicCompany) {
            // Xóa ảnh nếu có
            if ($musicCompany->photo && $musicCompany->photo !== 'backend/images/profile-6.jpg') {
                Storage::disk('public')->delete($musicCompany->photo);
            }
    
            // Kiểm tra và xóa tài nguyên nếu có (dựa vào cột 'resources' là JSON)
            if ($musicCompany->resources) {
                $resourcesArray = json_decode($musicCompany->resources, true);  // Chuyển cột JSON thành mảng PHP
    
                foreach ($resourcesArray as $resource) {
                    $resourceId = $resource['resource_id'];
    
                    // Xóa tài nguyên khỏi bảng 'resources'
                    $resourceRecord = Resource::find($resourceId);
                    if ($resourceRecord) {
                        if (Storage::disk('public')->exists($resourceRecord->url)) {
                            Storage::disk('public')->delete($resourceRecord->url);
                        }
    
                        $resourceRecord->delete();
                    }
                }
            }
    
            // Kiểm tra và xóa các tag liên kết nếu có
            if ($musicCompany->tags) {
                $tagsArray = json_decode($musicCompany->tags, true);  // Chuyển tags thành mảng
    
                if (is_array($tagsArray)) {
                    foreach ($tagsArray as $tagId) {
                        // Kiểm tra xem tag có còn được sử dụng bởi bất kỳ MusicCompany nào khác không
                        $isTagInUse = MusicCompany::where('tags', 'like', '%"'.$tagId.'"%')->where('id', '!=', $musicCompany->id)->exists();
    
                        // Nếu tag không còn được sử dụng, xóa nó khỏi bảng tags
                        if (!$isTagInUse) {
                            Tag::where('id', $tagId)->delete();
                        }
                    }
                }
            }
    
            // Xóa bản ghi công ty âm nhạc
            $musicCompany->delete();
    
            return redirect()->route('admin.musiccompany.index')->with('success', 'Xóa công ty âm nhạc và tài nguyên thành công!');
        }
    
        return back()->with('error', 'Không tìm thấy công ty âm nhạc!');
    }
    

    
    
    public function show($id)
    {
        // Lấy công ty âm nhạc theo ID
        $company = MusicCompany::findOrFail($id);
        
        // Kiểm tra nếu 'resources' không rỗng và là một chuỗi JSON hợp lệ
        $resourcesArray = [];
        if ($company->resources) {
            $resourcesArray = json_decode($company->resources, true);
        }
    
        // Lấy tất cả các resource_id từ chuỗi resources
        $resourceIds = array_column($resourcesArray, 'resource_id');
    
        // Truy vấn bảng Resource để lấy các tài nguyên liên quan nếu có
        $resources = Resource::whereIn('id', $resourceIds)->get();
        
        // Cấu hình menu và breadcrumb
        $active_menu = "musiccompany_list";
        $breadcrumb = '
         <li class="breadcrumb-item"><a href="' . route('admin.musiccompany.index') . '">Danh sách công ty âm nhạc</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chi tiết Công ty Âm nhạc</li>';
    
        // Trả về view với các dữ liệu cần thiết
        return view('MusicCompany::musiccompany.show', compact('company', 'resources', 'active_menu', 'breadcrumb'));
    }
    

    

    public function createSlug($title)
    {
        return Str::slug($title);
    }
    // MusicCompanyController.php
    public function deleteResource(Request $request)
    {
        $musicCompany = musicCompany::find($request->input('company_id'));
    
        if ($musicCompany) {
            // Tìm tài nguyên trong bảng 'resources'
            $resource = Resource::find($request->input('resource_id'));
            
            if ($resource) {
                // Xóa tệp tin nếu tồn tại
                if (Storage::disk('public')->exists($resource->url)) {
                    Storage::disk('public')->delete($resource->url);
                }
    
                // Xóa tài nguyên khỏi bảng resources
                $resource->delete();
    
                // Cập nhật trường resources trong bảng musicCompany
                $resources = json_decode($musicCompany->resources, true);
    
                // Lọc bỏ tài nguyên đã xóa
                $resources = array_filter($resources, function($item) use ($request) {
                    return $item['resource_id'] != $request->input('resource_id');
                });
    
                // Cập nhật lại cột resources
                $musicCompany->resources = json_encode(array_values($resources));
                $musicCompany->save();
    
                // Trả về thông báo thành công
                return response()->json([
                    'success' => true,
                    'message' => 'Tài nguyên đã được xóa thành công.'
                ]);
            }
    
            return response()->json([
                'success' => false,
                'message' => 'Tài nguyên không tồn tại.'
            ]);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'Bài hát không tồn tại.'
        ]);
    }
    
    
    public function status(Request $request, $id)
{
    // Kiểm tra công ty có tồn tại không
    $musicCompany = MusicCompany::find($id);
    if (!$musicCompany) {
        return response()->json(['msg' => 'Không tìm thấy công ty âm nhạc.'], 404);
    }

    // Cập nhật trạng thái
    $musicCompany->status = $request->mode;
    $musicCompany->save();

    // Trả về phản hồi thành công
    return response()->json(['msg' => 'Cập nhật thành công.']);
}



public function search(Request $request)
    {
        
        $func = "musiccompany_management";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="musiccompany_management";
            $searchdata =$request->datasearch;
            $music_companies = DB::table('music_companies')->where('title','LIKE','%'.$request->datasearch.'%')->orWhere('address','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
             <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.musiccompany.index').'">Danh sách Công ty Âm nhạc</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('MusicCompany::musiccompany.search', compact('music_companies', 'breadcrumb', 'searchdata', 'active_menu'));

        }
        else
        {
            return redirect()->route('admin.musiccompany.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
}

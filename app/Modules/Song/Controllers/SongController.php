<?php

namespace App\Modules\Song\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Modules\Song\Models\Song;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Modules\Resource\Models\Resource;
use App\Modules\Tag\Models\Tag;
use Illuminate\Support\Str;
use App\Modules\Composer\Models\Composer;
use App\Modules\Singer\Models\Singer;
class SongController extends Controller
{
    public function index()
    {
        $active_menu = "song_management";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách Bài Hát</li>'; 
        $songs = Song::with('resources')->paginate(10);
        $allResources = Resource::all();

        return view('Song::song.index', compact('songs', 'active_menu', 'allResources', 'breadcrumb'));
    }

    public function create()
    {
        $singers = Singer::all();
        $composers = Composer::all();
        $tags = Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
        $active_menu = "song_management";
        $breadcrumb = '
             <li class="breadcrumb-item"><a href="' . route('admin.song.index') . '">Danh sách bài hát</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm Bài Hát</li>';
        $resources = Resource::all();
        return view('Song::song.create', compact('singers','composers','resources','active_menu', 'breadcrumb', 'tags'));
    }

    public function store(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'tags' => 'nullable|array', // Kiểm tra tags có phải là mảng không
            'tags.*' => 'exists:tags,id',  // Kiểm tra các tag có sẵn
            'new_tags' => 'nullable|string',  // Kiểm tra các tag nhập thêm
            'status' => 'required|in:active,inactive',
            'composer_id' => 'required|exists:composers,id',
            'singer_id' => 'required|exists:singers,id',
            'resources' => 'nullable|array', // Validate resources as an array
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

        // Create new Song instance
        $song = new Song($validatedData);
        $song->slug = $this->createSlug($song->title);
        $song->status = $validatedData['status'];  // Set the status for the song
        $song->tags = json_encode($tagsArray);  // Lưu mảng tags dưới dạng JSON
        $song->save();

        // Save ID of song to a variable
        $song->id;

        // Initialize an empty array to store resource information
        $resourcesArray = [];

        // Handle resources and store in resources table
        if ($request->has('resources') && is_array($request->resources)) {
            foreach ($request->resources as $resource) {
                if ($resource instanceof \Illuminate\Http\UploadedFile) {
                    // Store resource in the 'uploads/resources' folder and get the path
                    $resourcePath = $resource->store('uploads/resources', 'public');
                    $resourceUrl = Storage::url($resourcePath);
                    $resourceUrl = Str::replaceFirst('http://localhost', '', $resourceUrl); // Remove "http://localhost" from URL

                    // Generate slug for resource from title
                    $slug = Str::slug($song->title) . '-' . Str::random(6);  // Create custom slug

                    // Get MIME type for the file
                    $fileType = $resource->getMimeType();  // Get MIME type instead of file extension

                    // Determine type_code based on file MIME type
                    $typeCode = 'OTHER';  // Default type_code in uppercase

                    if (strpos($fileType, 'image') !== false) {
                        $typeCode = 'IMAGE';
                    } elseif (strpos($fileType, 'audio') !== false || strpos($fileType, 'mp3') !== false) {
                        $typeCode = 'AUDIO';
                    } elseif (strpos($fileType, 'video') !== false || strpos($fileType, 'mp4') !== false) {
                        $typeCode = 'VIDEO';
                    } elseif (strpos($fileType, 'pdf') !== false) {
                        $typeCode = 'DOCUMENT';
                    }

                    // Store in resources table and get resource_id
                    $resourceRecord = Resource::create([
                        'song_id' => $song->id,  // Reference song_id instead of company_id
                        'title' => $song->title,
                        'slug' => $slug,
                        'url' => $resourceUrl,
                        'file_type' => $fileType,  // Store MIME type
                        'type_code' => $typeCode,  // Store the determined type_code in uppercase
                        'file_name' => $resource->getClientOriginalName(),
                        'file_size' => $resource->getSize(),
                        'path' => $resourcePath,
                    ]);

                    // Add resource_id to resources array
                    $resourcesArray[] = [
                        'song_id' => $song->id,  // Reference song_id instead of company_id
                        'resource_id' => $resourceRecord->id,
                    ];
                }
            }
        }

        // Save resources array to 'resources' column of Song as JSON
        if (!empty($resourcesArray)) {
            $song->resources = json_encode($resourcesArray);
            $song->save();
        }

        // Redirect back with success message
        return redirect()->route('admin.song.index')->with('success', 'Bài hát đã được thêm thành công.');
    }

    // Helper method for creating slugs

    public function edit($id)
{
    
    // Lấy danh sách các thẻ (tags) có trạng thái "active"
    $tags = Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
    // Thiết lập menu và breadcrumb
    $active_menu = "song_management";
    $breadcrumb = '
        <li class="breadcrumb-item"><a href="' . route('admin.song.index') . '">Danh sách bài hát</a></li>
        <li class="breadcrumb-item active" aria-current="page">Điều chỉnh bài hát</li>';
    // Lấy thông tin bài hát
    $song = Song::findOrFail($id);
    // Lấy danh sách nhạc sĩ và ca sĩ để chọn trong dropdown
    $composers = Composer::all();
    $singers = Singer::all();
    // Giải mã cột resources nếu có dữ liệu
    $resourcesArray = is_string($song->resources) ? json_decode($song->resources, true) : $song->resources;

    // Lấy tất cả các resource_id từ mảng resources đã gắn vào bài hát
    $resourceIds = array_column($resourcesArray, 'resource_id');
    
    // Nếu có resource_id, lấy danh sách tài nguyên đã gắn cho bài hát
    $resources = Resource::whereIn('id', $resourceIds)->get();

    // Lấy danh sách tài nguyên có thể thêm vào (tức là chưa được gắn)
    $availableResources = Resource::whereNotIn('id', $resourceIds)->get();

    // Giải mã cột tags nếu có dữ liệu
    $attachedTags = json_decode($song->tags, true) ?? []; // Mặc định là mảng rỗng nếu không có dữ liệu

    // Lấy tất cả các tài nguyên (resources) có thể gán vào bài hát
    $allResources = Resource::all();  // Lấy tất cả tài nguyên, có thể thêm điều kiện lọc nếu cần

    // Trả về view chỉnh sửa với các thông tin cần thiết
    return view('Song::song.edit', compact(
        'song', 'composers', 'singers','resources', 'availableResources', 'tags', 'attachedTags', 'allResources', 'active_menu', 'breadcrumb'
    ));
}

public function update(Request $request, $id)
{
    // Validate input
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'summary' => 'nullable|string',
        'content' => 'nullable|string',
        'tags' => 'nullable|array', // Kiểm tra tags có phải là mảng không
        'tags.*' => 'exists:tags,id',  // Kiểm tra các tag có sẵn
        'status' => 'required|string|in:active,inactive',
        'resources' => 'nullable|array', // Validate resources as an array
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

    // Find the Song to update by ID
    $song = Song::findOrFail($id);

    // Update Song data, without changing the ID
    $song->fill($validatedData);
    $song->slug = $this->createSlug($song->title);
    $song->tags = json_encode($tagsArray);  // Lưu mảng tags dưới dạng JSON
    $song->save();

    // Handle resources and update them
    if ($request->has('resources') && is_array($request->resources)) {
        // Optionally, you can clear old resources before adding new ones
        // $song->resources()->delete();  // Uncomment if you want to remove old resources

        // Initialize an empty array to store resource information
        $resourcesArray = [];

        foreach ($request->resources as $resource) {
            if ($resource instanceof \Illuminate\Http\UploadedFile) {
                // Store new resource in the 'uploads/resources' folder and get the path
                $resourcePath = $resource->store('uploads/resources', 'public');
                $resourceUrl = Storage::url($resourcePath);
                $resourceUrl = Str::replaceFirst('http://localhost', '', $resourceUrl); // Remove "http://localhost" from URL

                // Generate slug for resource from title
                $slug = Str::slug($song->title) . '-' . Str::random(6);  // Create custom slug

                // Get MIME type for the file
                $fileType = $resource->getMimeType();  // Get MIME type instead of file extension

                // Determine type_code based on file MIME type
                $typeCode = 'OTHER';  // Default type_code in uppercase

                if (strpos($fileType, 'image') !== false) {
                    $typeCode = 'IMAGE';
                } elseif (strpos($fileType, 'audio') !== false) {
                    $typeCode = 'AUDIO';
                } elseif (strpos($fileType, 'video') !== false) {
                    $typeCode = 'VIDEO';
                } elseif (strpos($fileType, 'pdf') !== false) {
                    $typeCode = 'DOCUMENT';
                }

                // Create a new resource record and keep the same song_id
                $resourceRecord = Resource::create([
                    'song_id' => $song->id,  // Keep the same song_id
                    'title' => $song->title,
                    'slug' => $slug,
                    'url' => $resourceUrl,
                    'file_type' => $fileType,  // Store MIME type
                    'type_code' => $typeCode,  // Store the determined type_code in uppercase
                    'file_name' => $resource->getClientOriginalName(),
                    'file_size' => $resource->getSize(),
                    'path' => $resourcePath,
                ]);

                // Add resource_id to resources array
                $resourcesArray[] = [
                    'song_id' => $song->id,
                    'resource_id' => $resourceRecord->id,
                ];
            }
        }

        // Update resources column with the new resources array, if applicable
        if (!empty($resourcesArray)) {
            $song->resources = json_encode($resourcesArray);
            $song->save();
        }
    }

  
    // Redirect back with success message
    return redirect()->route('admin.song.index')->with('success', 'Bài hát đã được cập nhật thành công.');
}
    public function destroy(string $id)
{
    $song = Song::find($id);
    
    if ($song) {
        // Xóa ảnh nếu có
        if ($song->photo && $song->photo !== 'backend/images/profile-6.jpg') {
            Storage::disk('public')->delete($song->photo);
        }
    
        // Kiểm tra và xóa tài nguyên nếu có (dựa vào cột 'resources' là JSON)
        if ($song->resources) {
            $resourcesArray = json_decode($song->resources, true);  // Chuyển cột JSON thành mảng PHP
    
            // Lấy ra tất cả resource_ids từ mảng JSON
            foreach ($resourcesArray as $resource) {
                // Lấy resource_id
                $resourceId = $resource['resource_id'];
    
                // Xóa tài nguyên khỏi bảng 'resources'
                $resourceRecord = Resource::find($resourceId);
                if ($resourceRecord) {
                    // Xóa tệp tin nếu tồn tại
                    if (Storage::disk('public')->exists($resourceRecord->path)) {
                        Storage::disk('public')->delete($resourceRecord->path);
                    }

                      // Kiểm tra và xóa các tag liên kết nếu có
            if ($song->tags) {
                $tagsArray = json_decode($song->tags, true);  // Chuyển tags thành mảng
    
                if (is_array($tagsArray)) {
                    foreach ($tagsArray as $tagId) {
                        // Kiểm tra xem tag có còn được sử dụng bởi bất kỳ song nào khác không
                        $isTagInUse = song::where('tags', 'like', '%"'.$tagId.'"%')->where('id', '!=', $song->id)->exists();
    
                        // Nếu tag không còn được sử dụng, xóa nó khỏi bảng tags
                        if (!$isTagInUse) {
                            Tag::where('id', $tagId)->delete();
                        }
                    }
                }
            }
    
                    // Xóa bản ghi tài nguyên
                    $resourceRecord->delete();
                }
            }
        }
    
        // Xóa bản ghi bài hát
        $song->delete();
    
        return redirect()->route('admin.song.index')->with('success', 'Xóa bài hát và tài nguyên thành công!');
    }
    
    return back()->with('error', 'Không tìm thấy bài hát!');
    }
    public function show($id)
{
    // Lấy bài hát theo ID
    $song = Song::findOrFail($id);
;
    // Kiểm tra nếu 'resources' không rỗng và là một chuỗi JSON hợp lệ
    $resourcesArray = [];
    if ($song->resources) {
        $resourcesArray = json_decode($song->resources, true);
    }

    // Lấy tất cả các resource_id từ chuỗi resources
    $resourceIds = array_column($resourcesArray, 'resource_id');
    
    // Truy vấn bảng Resource để lấy các tài nguyên liên quan nếu có
    $resources = Resource::whereIn('id', $resourceIds)->get();
    
    // Cấu hình menu và breadcrumb
    $active_menu = "song_list";
    $breadcrumb = '
        <li class="breadcrumb-item"><a href="' . route('admin.song.index') . '">Danh sách bài hát</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chi tiết Bài hát</li>';
    
    // Trả về view với các dữ liệu cần thiết
    return view('Song::song.show', compact('song', 'resources', 'active_menu', 'breadcrumb'));
    }
    public function createSlug($title)
{
    return Str::slug($title);
}
public function deleteResource(Request $request, $songId)
{
    $resourceId = $request->input('resource_id');
    
    // Tìm tài nguyên và xóa nó
    $resource = Resource::findOrFail($resourceId);
    $resource->delete();
    
    return response()->json(['success' => true]);
}
public function status(Request $request, $id)
{
    // Kiểm tra bài hát có tồn tại không
    $song = Song::find($id);
    if (!$song) {
        return response()->json(['msg' => 'Không tìm thấy bài hát.'], 404);
    }

    // Cập nhật trạng thái
    $song->status = $request->mode;
    $song->save();

    // Trả về phản hồi thành công
    return response()->json(['msg' => 'Cập nhật thành công.']);
}

}

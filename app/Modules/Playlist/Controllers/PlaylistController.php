<?php

namespace App\Modules\Playlist\Controllers;
use Illuminate\Support\Arr; 
use Illuminate\Support\Facades\Auth;
use App\Modules\Playlist\Models\Playlist; // Mô hình Playlist
use App\Modules\Song\Models\Song; // Mô hình Song (nếu bạn có sử dụng song trong Playlist)
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PlaylistController extends Controller
{
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','10');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $playlists = Playlist::paginate(10);
        $active_menu = "playlist_management";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách Playlists</li>'; 
        $songs = Song::with('resources')->paginate(10);
        return view('Playlist::playlist.index', compact('playlists','songs', 'active_menu',  'breadcrumb'));
    }

    public function create()
    {
        $active_menu = "playlist_management";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.playlist.index') . '">Danh sách Playlist</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm Playlist</li>';

        $songs = Song::all();  // Nếu bạn muốn danh sách bài hát trong playlist

        return view('Playlist::playlist.create', compact('songs', 'active_menu', 'breadcrumb'));
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'photo' => 'string|nullable',
            'song_id' => 'required|array',    // Kiểm tra song_id là mảng
            'song_id.*' => 'exists:songs,id',  // Kiểm tra từng ID bài hát tồn tại trong bảng songs
        ]);
        
        // Xử lý tiêu đề playlist
        $validatedData['title'] = strip_tags($validatedData['title']);
        
        // Tạo slug nếu không có
        $validatedData['slug'] = $validatedData['slug'] ?? $this->createSlug($validatedData['title']);
        
        // Lấy ID người dùng hiện tại
        $validatedData['user_id'] = Auth::id();
        
        // Tính toán order_id mới
        // Lấy order_id lớn nhất hiện tại của playlist của người dùng
        $lastOrder = Playlist::where('user_id', Auth::id())->max('order_id');
        $validatedData['order_id'] = $lastOrder + 1;  // Tăng order_id lên 1
        
        // Tạo playlist mới với dữ liệu đã xác thực
        $playlist = new Playlist($validatedData);
        
        // Lưu song_id dưới dạng mảng JSON
        $playlist->song_id = json_encode($validatedData['song_id']);  // Chuyển đổi mảng ID bài hát thành JSON
        
        // Lưu kiểu playlist
        $playlist->type = $request->type;
        
        // Lưu playlist vào cơ sở dữ liệu
        $playlist->save();
        
        // Chuyển hướng và thông báo thành công
        return redirect()->route('admin.playlist.index')->with('success', 'Playlist đã được thêm thành công.');
    }
    
    

    public function edit($id)
{
    $active_menu = "playlist_management";
    $breadcrumb = '
        <li class="breadcrumb-item"><a href="' . route('admin.playlist.index') . '">Danh sách Playlist</a></li>
        <li class="breadcrumb-item active" aria-current="page">Điều chỉnh Playlist</li>';
    
    // Lấy playlist dựa trên id
    $playlist = Playlist::findOrFail($id);

    // Lấy tất cả bài hát để hiển thị trong dropdown
    $songs = Song::all();

    // Chuyển chuỗi JSON song_id thành mảng để hiển thị trong form
    $selectedSongs = json_decode($playlist->song_id, true) ?? [];

    return view('Playlist::playlist.edit', compact('playlist', 'songs', 'selectedSongs', 'active_menu', 'breadcrumb'));
}


public function update(Request $request, $id)
{
    $playlist = Playlist::findOrFail($id);

    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'photo' => 'string|nullable',
        'slug' => 'nullable|string|max:255',
        'song_id' => 'required|array',
        'type' => 'required|string|in:public,private', // Kiểm tra loại playlist
    ]);

    

    // Kiểm tra và tạo slug mới nếu title thay đổi
    if ($playlist->title !== $request->title) {
        $playlist->slug = $this->createSlug($request->title);
    }

    $validatedData['photo'] = $validatedData['photo'] ?? $playlist->photo;
        if (empty($validatedData['photo'])) {
            $validatedData['photo'] = asset('backend/images/profile-6.jpg');
        }

    // Điền các giá trị từ $validatedData, ngoại trừ song_id
    $playlist->fill(Arr::except($validatedData, ['song_id']));

    // Chuyển mảng song_id thành chuỗi JSON trước khi lưu
    $playlist->song_id = json_encode($validatedData['song_id']);
    $playlist->save();

    return redirect()->route('admin.playlist.index')->with('success', 'Playlist đã được cập nhật thành công.');
}


    public function destroy($id)
    {
        $playlist = Playlist::find($id);

        if ($playlist) {
            // Xóa playlist
          
            $playlist->delete();
            return redirect()->route('admin.playlist.index')->with('success', 'Xóa Playlist thành công!');
        } else {
            return back()->with('error', 'Không tìm thấy Playlist!');
        }
    }

    public function show($id)
    {
        // Lấy playlist
        $playlist = Playlist::findOrFail($id);
    
        // Giả sử song_id lưu dưới dạng mảng
        $songIds = json_decode($playlist->song_id); // Chuyển đổi từ chuỗi JSON thành mảng
    
        // Lấy các bài hát dựa trên song_id
        $songs = Song::whereIn('id', $songIds)->get();
    
        // Đảm bảo bạn truyền các dữ liệu vào view
        $active_menu = 'playlist_management';
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.playlist.index') . '">Danh sách Playlist</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chi Tiết Playlist</li>';
    
        return view('Playlist::playlist.show', compact('playlist', 'songs', 'active_menu', 'breadcrumb'));
    }
    
    

    protected function createSlug($title)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }


    public function search(Request $request)
    {
        
        $func = "playlist_management";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="playlist_management";
            $searchdata =$request->datasearch;
            $playlists = DB::table('playlists')->where('title','LIKE','%'.$request->datasearch.'%')->orWhere('type','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
             <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.playlist.index').'">Danh sách Playlist</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('Playlist::playlist.search', compact('playlists', 'breadcrumb', 'searchdata', 'active_menu'));

        }
        else
        {
            return redirect()->route('admin.playlist.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
}

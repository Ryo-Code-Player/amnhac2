<?php

namespace App\Modules\Listener\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use App\Modules\Listener\Models\Listener; // Mô hình Listener
use App\Modules\Song\Models\Song; // Mô hình Song nếu bạn sử dụng song trong Listener
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\MusicType\Models\MusicType; 
use App\Modules\Singer\Models\Singer; 
use App\Modules\Composer\Models\Composer; 
use Illuminate\Support\Facades\DB;


class ListenerController extends Controller
{
    
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','10');
        $this->middleware('auth');
        
    }
    public function index()
    {
        
        $listeners = Listener::paginate(10);
        $active_menu = "listener_management";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách Listener</li>'; 
            $songs = Song::all();  // Nếu bạn muốn danh sách bài hát trong Listener
        return view('Listener::listener.index', compact('listeners', 'songs', 'active_menu', 'breadcrumb'));
    }

    public function create()
    {
        $composers = Composer::all();
        $singers = Singer::all();
        $music_types = MusicType::all(); // Lấy tất cả các thể loại âm nhạc
        $active_menu = "listener_management";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.listener.index') . '">Danh sách Listener</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm Listener</li>';

        $songs = Song::all();  // Nếu bạn muốn danh sách bài hát trong Listener

        return view('Listener::listener.create', compact('composers','singers','music_types','songs', 'active_menu', 'breadcrumb'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'favorite_type' => 'nullable|string|max:255',
            'favorite_song' => 'nullable|string|max:255',
            'favorite_singer' => 'nullable|string|max:255',
            'favorite_composer' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,inactive',
        ]);

        // Tạo một Listener mới với dữ liệu đã xác thực
        $listener = new Listener($validatedData);
        $listener->save();

        return redirect()->route('admin.listener.index')->with('success', 'Listener đã được thêm thành công.');
    }
    
    public function edit($id)
    {
        $composers = Composer::all();
        $singers = Singer::all();
        $songs = Song::all();  // Nếu bạn muốn danh sách bài hát trong Listener
        $music_types = MusicType::all(); // Lấy tất cả các thể loại âm nhạc
        $active_menu = "listener_management";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.listener.index') . '">Danh sách Listener</a></li>
            <li class="breadcrumb-item active" aria-current="page">Điều chỉnh Listener</li>';

        $listener = Listener::findOrFail($id);

        $songs = Song::all();
        return view('Listener::listener.edit', compact('songs','composers','singers','music_types','listener', 'songs', 'active_menu', 'breadcrumb'));
    }

    public function update(Request $request, $id)
    {
        // Xác nhận các dữ liệu đầu vào
        $request->validate([
            'favorite_type' => 'required|string',
            'favorite_song' => 'nullable|exists:songs,id',
            'favorite_singer' => 'nullable|exists:singers,id',
            'favorite_composer' => 'nullable|exists:composers,id',
            'status' => 'required|string|in:active,inactive',
        ]);

        // Tìm Listener theo id
        $listener = Listener::findOrFail($id);

        // Cập nhật thông tin listener
        $listener->update([
            'favorite_type' => $request->input('favorite_type'),
            'favorite_song' => $request->input('favorite_song'),
            'favorite_singer' => $request->input('favorite_singer'),
            'favorite_composer' => $request->input('favorite_composer'),
            'status' => $request->input('status'),
        ]);

        // Quay lại danh sách Listener với thông báo thành công
        return redirect()->route('admin.listener.index')->with('success', 'Listener updated successfully!');
    }

    public function destroy($id)
    {
        $listener = Listener::find($id);

        if ($listener) {
            $listener->delete();
            return redirect()->route('admin.listener.index')->with('success', 'Xóa Listener thành công!');
        } else {
            return back()->with('error', 'Không tìm thấy Listener!');
        }
    }

    public function show($id)
    {
       // Lấy thông tin của listener cùng với các thông tin về favorite song, singer, và composer
  // Lấy thông tin của listener cùng với các thông tin về favorite song, singer, và composer
  $listener = Listener::with(['favoriteSong', 'favoriteSinger', 'favoriteComposer'])->findOrFail($id);
    
        
        $active_menu = 'listener_management';
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.listener.index') . '">Danh sách Listener</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chi Tiết Listener</li>';

        return view('Listener::listener.show', compact('listener', 'active_menu', 'breadcrumb'));
    }

    public function status(Request $request, $id)
{
    // Kiểm tra Listener có tồn tại không
    $listener = Listener::find($id);
    if (!$listener) {
        return response()->json(['msg' => 'Không tìm thấy listener.'], 404);
    }

    // Cập nhật trạng thái
    $listener->status = $request->mode;  // mode là giá trị trạng thái bạn muốn cập nhật
    $listener->save();

    // Trả về phản hồi thành công
    return response()->json(['msg' => 'Cập nhật trạng thái thành công.']);
}

    
public function search(Request $request)
    {
        
        $func = "listener_management";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="listener_management";
            $searchdata =$request->datasearch;
            $listeners = DB::table('listeners')->where('favorite_type','LIKE','%'.$request->datasearch.'%')->orWhere('favorite_song','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
             <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.listener.index').'">Danh sách Listener</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('Listener::listener.search', compact('listeners', 'breadcrumb', 'searchdata', 'active_menu'));

        }
        else
        {
            return redirect()->route('admin.listener.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
}

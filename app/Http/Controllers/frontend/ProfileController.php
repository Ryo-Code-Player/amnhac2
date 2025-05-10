<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Blog\Models\Blog;
use App\Modules\Playlist\Models\Playlist;
use App\Modules\Singer\Models\Singer;
use App\Modules\Song\Models\Song;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        // $this->middleware('admin.auth');
    }
    public function index()
    {
        //
        $user = auth()->user();
        
        $blogs = Blog::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        $playlist = Playlist::where('user_id', $user->id)->orderBy('created_at','desc')->get();

        return view('frontend.profile.master', [
            'full_name' => $user->full_name,
            'photo' => $user->photo,
            'id' => $user->id,
            'description' => $user->description,
        ], compact('blogs', 'playlist'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function playlist()
    {
        // if(!auth()->check())
        // {
        //     return redirect('/');
        // }

        $playlist = Playlist::with('user')->where('type','public')->get();
        if(auth()->check())
        {
            $playlist_user = Playlist::with('user')->where('user_id', auth()->user()->id)->get();
        }else{
            $playlist_user = [];
        }
        return view('frontend.profile.playlist', compact('playlist','playlist_user'));
    }

    public function playlist_slug($slug)
    {
        $playlist = Playlist::with('user')->where('slug', $slug)->first();
        $song =$playlist->song_id ? json_decode($playlist->song_id) :[];
        $array_song = [];
        foreach ($song as $item) {
           
            $song_id = Song::with('singer')->find($item);
            $array_song[] = $song_id;
        }

        $songs = [];
        foreach ($array_song as $s) {
            if (!$s) continue; 
            $songs[] = [
                'title' => $s->title,
                'artist' => $s->singer->alias ?? '',
                'src' => asset(str_replace(':8000/', '', $s->resourcesSong[0]->url ?? '')),
                'thumb' => asset($s->singer->photo ?? ''),
            ];
        }
        return view('frontend.profile.playlist_slug', compact('playlist','songs','array_song'));
    }

    public function createPlaylist(Request $request)
    {
        $order = Playlist::count();
      
        $playlist = new Playlist();
        $playlist->title = $request->playlistName;
        $playlist->slug = Str::slug($request->playlistName);
        $playlist->type = $request->playlistType;
        if($request->file('playlistImage'))
        {
            $filename = $request->file('playlistImage')->getClientOriginalName();
            $path = $request->file('playlistImage')->storeAs('avatar', $filename);
            $playlist->photo = asset('storage/'.$path);
        }
        $playlist->user_id = auth()->user()->id;
        $playlist->song_id = json_encode($request->song_id ?? []);
        $playlist->order_id = $order + 1;
        $playlist->save();

        return redirect()->route('front.playlist')->with('success', 'Playlist đã được tạo thành công');
    }

    public function deletePlaylist($id)
    {
        $playlist = Playlist::find($id);
        $playlist->delete();
        return redirect()->route('front.playlist')->with('success', 'Playlist đã được xóa thành công');
    }

    public function addSongToPlaylist(Request $request, $id)
    {
       
        $playlist = Playlist::find($id);
       
        $playlist_song = json_decode($playlist->song_id ?? []);
      
        $song = Song::where('title', $request->song_id)->first();
            if(!$song)
            {
                $song = Song::where('id', $request->song_id)->first();
            }
        
        if (!in_array($song->id, $playlist_song)) {
            $playlist_song[] = $song->id;
            $playlist->song_id = json_encode($playlist_song);
            $playlist->save();
            return response()->json(['success' => true, 'message' => 'Bài hát đã được thêm vào playlist']);
        } else {
            return response()->json(['success' => false, 'message' => 'Bài hát đã tồn tại trong playlist']);
        }
    }


    public function zingchart(Request $request){
        $song = Song::where('status', 'active')->with('singer');
        
        if($request->time == 'newest'){
            $song->orderBy('id', 'desc');
        }
        if($request->time == 'oldest'){
            $song->orderBy('id', 'asc');
        }
        if($request->alpha == 'az'){
            $song->orderBy('title', 'asc');
        }
        if($request->alpha == 'za'){
            $song->orderBy('title', 'desc');
        }
        if($request->artist){
            $song->whereHas('singer', function($query) use ($request){
                $query->where('alias', 'like', '%'.$request->artist.'%');
            });
        }
        if($request->title){
            $song->where('title', 'like', '%'.$request->title.'%');
        }
        
        

        $song = $song->get();
        $songs = $song->map(function($s) {
            return [
                'title' => $s->title,
                'artist' => $s->singer->alias ?? auth()->user()->full_name,
                'src' => asset(str_replace(':8000/', '', $s->resourcesSong[0]->url)),
                'thumb' => asset($s->singer->photo ?? 'storage/' . auth()->user()->photo),
            ];
        });
        $array_view = [];
        $array_title = [];
        $song_view = Song::where('status', 'active')
        ->whereNotNull('view')
        ->orderBy('view', 'desc')
        ->limit(10)
        ->get();
    
        foreach ($song_view as $s) {
            $array_view[] = (int)$s->view;
            $array_title[] = $s->title;
        }
        
        return view('frontend.zingchart.index', compact('songs','song','array_view','array_title'));
    }

    public function zingsinger(){
        
        $Singer = Singer::where('status', 'active')->orderBy('id', 'desc')->get();
      
        return view('frontend.zingchart.singer', compact('Singer'));
    }

    public function zingsinger_slug($slug){
        $Singer = Singer::with('song')->where('slug', $slug)->first();
        $suggestedSingers = Singer::where('status', 'active')->orderBy('id', 'desc')->limit(5)->get();
        return view('frontend.zingchart.singer_slug', compact('Singer','suggestedSingers'));
    }
}

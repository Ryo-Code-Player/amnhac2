<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Post;
use App\Modules\Event\Models\Event;
use App\Modules\Event\Models\EventUser;
use App\Modules\MusicType\Models\MusicType;
use App\Modules\Song\Models\Song;
use App\Modules\Singer\Models\Singer;
use Illuminate\Support\Facades\Storage;
use App\Modules\Resource\Models\Resource;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct( )
    {

    }
    public function index()
    {
        $song = Song::where('status', 'active')->with('singer')
        ->orderBy('id', 'desc')
        ->limit(6)
        ->get();
        $songs = $song->map(function($s) {
            return [
                'title' => $s->title,
                'artist' => $s->singer->alias ?? (auth()->user()->full_name ?? null),
                'src' => asset(str_replace(':8000/', '', $s->resourcesSong[0]->url)),
                'thumb' => asset($s->singer->photo ?? (auth()->user()->photo ?? null)),
            ];
        });
     
        $blog = Blog::where('status', 'active')->orderBy('id', 'desc')->limit(5)->get();

        $Singer = Singer::where('status', 'active')->orderBy('id', 'desc')->limit(4)->get();
        
        return view ('frontend.layouts.content', compact('songs','song','blog','Singer'));

    }

    public function cate()
    {
        $cate = MusicType::where('status', 'active')->get();
        return view ('frontend.cate.master', compact('cate'));
   
        // echo 'i am admin';
    }

    public function singer()
    {
        //
        $singer = Singer::where('status', 'active')->get();
        return view ('frontend.singer.master', compact('singer'));
   
        // echo 'i am admin';
    }

    public function song()
    {
        $songs = Song::with('singer')->where('status', 'active')->get();
        return view ('frontend.songs.master', compact('songs'));
   
        // echo 'i am admin';
    }
    
    public function detail($slug)
    {
        $song = Song::with('singer')->where('slug', $slug)->first();
   
        $resourcesArray = [];
        if ($song->resources) {
            $resourcesArray = json_decode($song->resources, true);
        }

        // Lấy tất cả các resource_id từ chuỗi resources
        $resourceIds = array_column($resourcesArray, 'resource_id');
        
        // Truy vấn bảng Resource để lấy các tài nguyên liên quan nếu có
        $resources = Resource::whereIn('id', $resourceIds)->get();

        if (!$song) {
            return abort(404);
        }

        return view('frontend.songs.detail.master', compact('song', 'resources'));
    }

    public function topic()
    {
        //
        return view ('frontend.topic.master');
   
        // echo 'i am admin';
    }
    

    public function blog()
    {
        //
        return view ('frontend.blog.master');
   
        // echo 'i am admin';
    }


    // public function user()
    // {
    //     //
    //     return view ('frontend.user.master');
   
    //     // echo 'i am admin';
    // }
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

    public function songView(Request $request)
    {
       
        $song = Song::where('id',$request->song_id)->first();
       
        $song->view = ($song->view ?? 0) + 1;
        $song->save();
        return response()->json(['message' => 'View updated']);
    }

    public function eventRegistrations(Request $request)
    {
        
       
        $event = EventUser::orderBy('id','desc')->
        where('user_id',$request->id)->with('event','user','role')->get();
      
        return response()->json($event);
    }


    public function songShare(Request $request)
    {   

       
          
            $song = Song::where('id',$request->id)->with('singer')->first();
         
            $post = Post::create([
                'description' => $song->title . ' - ' . $request->url,
                'link' => $request->url,
                'user_id' => auth()->user()->id,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'like' => 0,
                'view' => 0,
                'comment' => 0,
                'share' => 0,   
                'post_singer'=> optional($song->singer)->id ?? null        
        ]);
        return redirect()->back()->with('success','Bài hát đã được chia sẻ thành công');
     
    }
}

<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        //
        return view ('frontend.layouts.master');
   
        // echo 'i am admin';
    }

    public function cate()
    {
        //
        return view ('frontend.cate.master');
   
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
}

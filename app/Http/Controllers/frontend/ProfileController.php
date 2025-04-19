<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Blog\Models\Blog;
use App\Modules\Playlist\Models\Playlist;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('admin.auth');
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
}

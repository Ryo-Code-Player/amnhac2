<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Blog\Models\Blog;
use App\Modules\Song\Models\Song;
use App\Modules\MusicType\Models\MusicType;
use App\Modules\Playlist\Models\Playlist;
use App\Modules\Singer\Models\Singer;
use Illuminate\Support\Facades\DB;

class SongFrontController extends Controller
{
    public function song()
    {
        $songs = Song::with('singer', 'musicType')
            ->where('status', 'active')
            ->get();
        $musicTypes = MusicType::where('status', 'active')->get();

        return view('frontend.songs.master', compact('songs', 'musicTypes'));
    }

    public function songByCategory($slug)
    {
        
        $musicType = MusicType::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $songs = Song::with('singer', 'musicType')
            ->where('musictype_id', $musicType->id)
            ->where('status', 'active')
            ->get();
        $cate = MusicType::where('status', 'active')->get();

        return view('frontend.songs.master', compact('songs', 'cate', 'musicType'));
    }

    public function songBySinger($slug)
    {
        $singer = Singer::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $songs = Song::with('singer', 'musicType')
            ->where('singer_id', $singer->id)
            ->where('status', 'active')
            ->get();
        $cate = MusicType::where('status', 'active')->get();

        return view('frontend.songs.master', compact('songs', 'cate', 'singer'));
    }

    public function songByPlaylist($slug)
    {
        $playlist = Playlist::where('slug', $slug)->firstOrFail();

        $songIds = json_decode($playlist->song_id, true); 

        $songs = Song::with('singer', 'musicType')
            ->whereIn('id', $songIds)
            ->where('status', 'active')
            ->get();

        $cate = MusicType::where('status', 'active')->get();

        return view('frontend.songs.master', compact('songs', 'cate', 'playlist'));
    }

    public function searchSong(Request $request)
    {
        $searchdata =$request->datasearch;
        $songs = Song::with('singer', 'musicType')
        ->where('title', 'LIKE', '%' . $searchdata . '%')
        ->orWhere('content', 'LIKE', '%' . $searchdata . '%')
        ->get();

        return view('frontend.songs.master', compact('songs', 'searchdata'));
    }
}

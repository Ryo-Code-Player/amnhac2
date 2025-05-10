<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Blog\Models\Blog;
use App\Modules\MusicType\Models\MusicType;
use App\Modules\Singer\Models\Singer;
use App\Modules\Song\Models\Song;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
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
        $category = MusicType::where('status', 'active')->with('song')->get();
        $blog_cate = DB::table('blog_categories')->where('status', 'active')->orderBy('updated_at', 'desc')->get();
        // dd($category);
        return view('frontend.category.index', compact('category','blog_cate'));
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

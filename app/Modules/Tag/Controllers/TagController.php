<?php

namespace App\Modules\Tag\Controllers;

use App\Modules\Tag\Models\Tag;
use App\Modules\Resource\Models\Resource;
use App\Modules\Blog\Models\Blog;
use App\Modules\MusicCompany\Models\MusicCompany;
use App\Modules\Singer\Models\Singer;
use App\Modules\Song\Models\Song; // Nạp thêm model Song
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','10');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $active_menu = "tag_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách Tag</li>';
        $tags = Tag::with(['resources', 'blogs', 'musicCompanies', 'singers', 'songs'])->paginate(10); // Thêm songs
        return view('Tag::tag.index', compact('tags', 'active_menu', 'breadcrumb'));
    }

    public function create()
    {
        $active_menu = "tag_list";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="' . route('admin.tag.index') . '">Danh sách Tag</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm Tag Mới</li>';
        $resources = Resource::all();
        $blogs = Blog::all();
        $musicCompanies = MusicCompany::all();
        $singers = Singer::all(); // Lấy tất cả singers
        $songs = Song::all(); // Lấy tất cả songs
        return view('Tag::tag.create', compact('resources', 'blogs', 'musicCompanies', 'singers', 'songs', 'active_menu', 'breadcrumb'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'resources' => 'array',
            'blogs' => 'array',
            'music_companies' => 'array',
            'singers' => 'array', // Thêm singers vào danh sách cần validate
            'songs' => 'array', // Thêm songs vào danh sách cần validate
        ]);

        $tag = Tag::create($request->only('title'));

        if ($request->resources) {
            $tag->resources()->attach($request->resources);
        }

        if ($request->blogs) {
            $tag->blogs()->attach($request->blogs);
        }

        if ($request->music_companies) {
            $tag->musicCompanies()->attach($request->music_companies);
        }

        if ($request->singers) {
            $tag->singers()->attach($request->singers); // Thêm liên kết singers
        }

        if ($request->songs) {
            $tag->songs()->attach($request->songs); // Thêm liên kết songs
        }

        return redirect()->route('admin.tag.index')->with('success', 'Tag created successfully.');
    }

    public function edit(Tag $tag)
    {
        $active_menu = "tag_list";
        $breadcrumb = '
                <li class="breadcrumb-item"><a href="' . route('admin.tag.index') . '">Danh sách Tag</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh Sửa Tag</li>';
        $resources = Resource::all();
        $blogs = Blog::all();
        $musicCompanies = MusicCompany::all();
        $singers = Singer::all(); // Lấy tất cả singers
        $songs = Song::all(); // Lấy tất cả songs
        return view('Tag::tag.edit', compact('tag', 'resources', 'blogs', 'musicCompanies', 'singers', 'songs', 'active_menu', 'breadcrumb'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'resources' => 'array',
            'blogs' => 'array',
            'music_companies' => 'array',
            'singers' => 'array', // Thêm singers vào danh sách cần validate
            'songs' => 'array', // Thêm songs vào danh sách cần validate
        ]);

        $tag->update($request->only('title'));

        $tag->resources()->sync($request->resources);
        $tag->blogs()->sync($request->blogs);
        $tag->musicCompanies()->sync($request->music_companies);
        $tag->singers()->sync($request->singers); // Cập nhật liên kết singers
        $tag->songs()->sync($request->songs); // Cập nhật liên kết songs

        return redirect()->route('admin.tag.index')->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tag.index')->with('success', 'Tag deleted successfully.');
    }

    public function search(Request $request)
    {
        
        $func = "tag_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="tag_list";
            $searchdata =$request->datasearch;
            $tags = DB::table('tags')->where('title','LIKE','%'.$request->datasearch.'%')->orWhere('status','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
             <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.tag.index').'">Danh sách tags</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('Tag::tag.search', compact('tags', 'breadcrumb', 'searchdata', 'active_menu'));

        }
        else
        {
            return redirect()->route('admin.tag.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
}

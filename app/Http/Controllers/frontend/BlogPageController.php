<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Blog\Models\Blog;

class BlogPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct( )
    {

    }
    public function index()
    {
        $blogs = Blog::select('id', 'title', 'slug', 'photo', 'summary', 'user_id', 'created_at')
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'full_name', 'photo');
                },
                'Tcomments' => function ($query) {
                    $query->where('parent_id', 0)
                          ->select('id', 'item_id', 'user_id', 'content', 'parent_id', 'created_at')
                          ->with(['user' => function ($q) {
                              $q->select('id', 'full_name', 'photo');
                          }])
                          ->take(3)
                          ->latest();
                },
                'Tmotion' => function ($query) {
                    $query->select('id', 'item_id', 'item_code', 'motions', 'user_motions');
                }
            ])
            ->latest()
            ->get();
            // dd(1);   
            // dd($blogs);
        return view('frontend.blog.index_blog', compact('blogs'));
    }

    public function loadComments(Request $request, $blogId)
    {
        $request->validate(['offset' => 'integer|min:0']);
        $offset = $request->input('offset', 0);
        $limit = 3;

        $comments = \App\Modules\TuongTac\Models\TComment::where('item_id', $blogId)
            ->where('item_code', 'blog')
            ->where('parent_id', 0)
            ->select('id', 'item_id', 'user_id', 'content', 'parent_id', 'created_at')
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'full_name', 'photo');
                },
                'replies' => function ($query) {
                    $query->select('id', 'item_id', 'user_id', 'content', 'parent_id', 'created_at')
                          ->with(['user' => function ($q) {
                              $q->select('id', 'full_name', 'photo');
                          }]);
                }
            ])
            ->skip($offset)
            ->take($limit)
            ->latest()
            ->get();

        return response()->json([
            'comments' => $comments,
            'hasMore' => $comments->count() === $limit
        ]);
    }

    public function detail($id)
    {
        // $blogs = Blog::select('id', 'title', 'slug', 'photo', 'summary', 'user_id', 'created_at')
        //     ->where('slug', $id)
        //     ->with([
        //         'user' => function ($query) {
        //             $query->select('id', 'full_name', 'photo');
        //         },
        //         'Tcomments' => function ($query) {
        //             $query->where('parent_id', 0)
        //                   ->select('id', 'item_id', 'user_id', 'content', 'parent_id', 'created_at')
        //                   ->with(['user' => function ($q) {
        //                       $q->select('id', 'full_name', 'photo');
        //                   }])
        //                   ->take(3)
        //                   ->latest();
        //         },
        //         'Tmotion' => function ($query) {
        //             $query->select('id', 'item_id', 'item_code', 'motions', 'user_motions');
        //         }
        //     ])
        //     ->Where('id', $id)
        //     ->latest()
        //     ->get();4
    
        $blogs = Blog::where('slug', $id)->with('user')->first();
        // dd($blogs);
        return view('frontend.blog.edit', compact('blogs'));
    }
}

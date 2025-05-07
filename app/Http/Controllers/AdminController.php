<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use App\Modules\Fanclub\Models\Fanclub;
use App\Modules\Song\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('admin.auth');
    }
    public function index()
    {
        //
        if(auth()->user()->role != 'admin')
        {
            return redirect('/');
        }
        $func = "admin_view";
    
        if(!$this->check_function($func))
        {
            return redirect()->route('home');
        }
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Bảng điều khiển</li>';
       $data['active_menu']='dashboard';
       $data['total_song'] = Song::where('status', 'active')->count();
       $data['total_user'] = User::where('status', 'active')->where('role', 'customer')->count();
       $data['total_club'] = Fanclub::where('status', 'active')->count();
       $data['total_post'] = Blog::where('status', 'active')->count();
       $array_view = [];
       $array_title = [];
       $song_view = Song::where('status', 'active')
       ->whereNotNull('view')
     
       ->limit(10)
       ->orderBy('view', 'desc')
       ->get();
       
       foreach ($song_view as $s) {
           $array_view[] = (int)$s->view;
           $array_title[] = $s->title;
       }
       $data['array_view'] = $array_view;
       $data['array_title'] = $array_title;
        return view ('backend.index',   $data);
   
        // echo 'i am admin';
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

    public function updateProfile123(Request $request)
    {
       
        $user = User::find($request->id);
      
        $user->full_name = $request->name;
  
        $user->phone = $request->phone ?? '';
        if($request->password)
        {
            $user->password = Hash::make($request->password);
        }
        if($request->avatar)
        {
         
                $filename = $request->file('avatar')->getClientOriginalName();

                // Lưu vào storage/app/public/avatars với tên gốc
                $path = $request->file('avatar')->storeAs('avatar', $filename);
              
                // Lưu vào database:
                $user->photo = str_replace('public/', 'storage/', $path);

        }
        $user->save();

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }
}

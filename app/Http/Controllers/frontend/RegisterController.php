<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
    }
    public function index()
    {
        //
        return view('frontend.user.master');
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
        $func = "user_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'full_name'=>'string|required',
            'email'=>'email|nullable',
            'photo'=>'string|nullable',
            'phone'=>'string|required',
            'password'=>'required|string|confirmed',
            'status'=>'nullable|in:active,inactive',
        ]);

        $data = $request->all();

        if($request->photo == null)
            $data['photo'] = asset('backend/images/profile-6.jpg');
        if($request->photo != null)
        {
            $photos = explode(',', $data['photo']);
            if(count ($photos) > 0)
                $data['photo'] = $photos[0];
        }
        if($request->email == null)
            $data['email'] = $data['full_name'].'@gmail.com';
        if($request->password == null)
            $data['password']=$data['full_name'];
        $olduser = User::where('email',$data['email'])->get();
        if(count($olduser) > 0)
                return back()->with('error','Email đã tồn tại!');
        $data['password'] = Hash::make($data['password']);
        $data['status'] = $data['status'] ?? 'active';
        $status = User::c_create($data);
        if($status){
            return redirect()->route('admin.login')->with('success','Tạo người dùng thành công!');
        }
        else
        {
            return back()->with('error','Something went wrong!');
        }    
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

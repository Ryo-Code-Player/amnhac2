<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
   
    public function viewLogin()
    {
        // dd(auth()->user());
        return view( 'backend.auths.login' );
        
    }
    public function logout(Request $request)
    {
        // Log the user out of the application
        \Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the session token to prevent session fixation attacks
        $request->session()->regenerateToken();

        // Redirect to the login page or home page
        // return redirect('admin/login');  // Or any other page you prefer
        return redirect('/');
        }

    public function login(Request $request)
    {
       
        $input = $request->all();
        // dd($input);
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
           
        ]);
       
        if(auth()->user())
        {
            return redirect()->route('admin.home');
        }
        $olduser = \App\Models\User::where('phone',$request->email )->orWhere('email',$request->email )->first();
        if($olduser)
        {
            if (auth()->attempt(array('email' => $olduser->email, 'password' => $request->password))) {
                if (auth()->user()) {
                    if (isset($request->plink) && $request->plink!= '' )
                    {
                        return redirect($request->plink);
                    }
                    else{
                        if(auth()->user()->role == 'customer'){
                            return redirect('/')->with('success', 'Đăng nhập thành công!');
                        }
                        else{
                            return redirect()->route('admin.home');
                        }
                     
                    }
                }
            } else {
                return redirect('/')->with('error', 'Email hoặc số điện thoại hoặc mật khẩu không đúng..');
                    // ->with('error', 'Email hoặc số điện thoại hoặc mật khẩu không đúng..');
            }
        }
        else {
            return redirect('/');
                // ->with('error', 'Email hoặc số điện thoại hoặc mật khẩu không đúng.');
        }
       
    }

    public function credentials(Request $request)
    {
        
        return ['email'=>$request->email,'password'=>$request->password, 'status'=>'active'];
    }
    public function viewAdminlogin()
    {
        return view('auth.admin.login');
    }


    public function register(Request $request)
    {

        
        $user = new \App\Models\User();
        $user->full_name = $request->name;
        $user->username = $request->email;
        $user->email = $request->email;
        $user->phone = $request->phone ?? '';
        $user->password = Hash::make($request->password);
        $user->save();
        // toastr()->success('Đăng ký thành công!');
        return redirect('/')->with('success', 'Đăng ký thành công!');
    }

    public function updateProfile123(Request $request)
    {
    
      
        $user = \App\Models\User::find(auth()->user()->id);
        $user->full_name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone ?? '';
        $user->save();
        return redirect('/')->with('success', 'Cập nhật thành công!');
    }
}

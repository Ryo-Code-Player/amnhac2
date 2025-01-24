<?php

namespace App\Http\Controllers\Api;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUser;
 
use Illuminate\Support\Facades\Http;
class AuthenticationController extends Controller
{
    public function loginWithGoogleToken(Request $request)
    {
        $accessToken = $request->input('accessToken');

        // Gửi request tới Google People API để lấy thông tin người dùng
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('https://www.googleapis.com/oauth2/v3/userinfo');
    
        if ($response->successful()) {
            $googleUser = $response->json();
    
            // Lấy thông tin người dùng
            $email = $googleUser['email'];
            $name = $googleUser['name'];
            $googleId = $googleUser['sub'];
            $picture = $googleUser['picture'];
             
            $data['password'] = '';
            $data['google_id'] = $googleId ;
            
            $data['phone'] = $googleId ;
            $data['full_name'] = $name;
            $data['email'] = $email;
            $data['photo'] = $picture;
            if ($data['email'] == null && $data['email'] == '')
                $data['email'] = $user->id.'@gmail.com';

            $user = User::where('google_id',  $data['google_id'])
            ->orWhere('email',$data['email'] )->orWhere('phone', $data['phone'])->first();

            if ($user) {

                Auth::login($user);
                $user->update(['google_id' =>  $googleId]);
            }  
            else
            {
                $user = User::create($data);
                Auth::login( $user );
            }
            $user_token = $user->createToken('appToken')->accessToken;
            if($user->photo == asset('/storage/avatar/OIP (3) (1)_UtNYV.jpg'))
            {
                $user->photo = $picture ;
                $user->save();
            }
            return response()->json([
                'success' => true,
                'token' => $user_token,
                'user' => $user,
            ], 200);
        } else {
            return response()->json(['error' => $response], 401);
        }
    }
    public function loginWithGoogleTokenId(Request $request)
    {
        $idToken = $request->input('id_token');

        // Sử dụng Google Client để xác thực idToken
        $client = new Google_Client(['client_id' => env('700742560819-59fbte0rrd2u4kkm2mpeer3ibh8nioj0.apps.googleusercontent.com')]); // Thay bằng Client ID của bạn
        $payload = $client->verifyIdToken($idToken);

        if ($payload) {
            // Lấy thông tin người dùng từ idToken
            $googleId = $payload['sub'];
            // $email = $payload['email'];
            // $name = $payload['name'];
            $data['password'] = '';
            $data['google_id'] = $googleId ;
            
            $data['phone'] = $googleId ;
            $data['full_name'] = $payload['name'];
            $data['email'] = $payload['email'];
            $data['photo'] = asset('/storage/avatar/OIP (3) (1)_UtNYV.jpg');
            if ($data['email'] == null && $data['email'] == '')
                $data['email'] = $user->id.'@gmail.com';

            $user = User::where('google_id',  $data['google_id'])
            ->orWhere('email',$data['email'] )->orWhere('phone', $data['phone'])->first();

            if ($user) {

                Auth::login($user);
                $user->update(['google_id' =>  $googleId]);
            }  
            else
            {
                $user = User::create($data);
                Auth::login( $user );
            }
            $user_token = $user->createToken('appToken')->accessToken;

            return response()->json([
                'success' => true,
                'token' => $user_token,
                'user' => $user,
            ], 200);
        } else {
            return response()->json(['error' => 'Invalid ID Token'], 401);
        }
    }

    public function loginWithFacebookToken(Request $request)
    {
        $accessToken = $request->input('access_token');

        try {
            $user = Socialite::driver('facebook')->userFromToken($accessToken);

            // Lấy thông tin từ Facebook
            $facebookId = $user->getId();
            $email = $user->getEmail();
            $name = $user->getName();
            $data['password'] = '';
            $data['facebook_id'] = $facebookId ;
            
            $data['phone'] = $facebookId ;
            $data['full_name'] = $name;
            $data['email'] =  $email;
            $data['photo'] = asset('/storage/avatar/OIP (3) (1)_UtNYV.jpg');
            if ($data['email'] == null && $data['email'] == '')
                $data['email'] = $user->id.'@gmail.com';

                $user = User::where('facebook_id',  $data['facebook_id'])
                ->orWhere('email',$data['email'] )->orWhere('phone', $data['phone'])->first();
    
                if ($user) {
    
                    Auth::login($user);
                    $user->update(['facebook_id' =>  $facebookId]);
                }  
                else
                {
                    $user = User::create($data);
                    Auth::login( $user );
                   
                }

            // Tạo token xác thực (JWT hoặc session token)
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'success' => true,
                'token' => $user_token,
                'user' => $user,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Invalid Access Token'], 401);
        }
    }
    public function store(Request $request)
    {
          
        if (auth()->attempt(array('email' =>$request->email, 'password' => $request->password))) {
          
        // if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            // successfull authentication
            $user = User::find(Auth::user()->id);
            if($user->status=='inactive')
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to authenticate.',
                ], 401);
            }
            else
            {
                $user_token['token'] = $user->createToken('appToken')->accessToken;

                return response()->json([
                    'success' => true,
                    'token' => $user_token,
                    'user' => $user,
                ], 200);
            }
           
        } else {
            // failure to authenticate
            return response()->json([
                'success' => false,
                'message' => 'Failed to authenticate.',
            ], 401);
        }
    }
        /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        if (Auth::user()) {
            $request->user()->token()->revoke();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ], 200);
        }
    }
    public function saveNewUser(Request $request)
    {
        $this->validate($request,[
            'full_name'=>'string|required',
            'description'=>'string|nullable',
            'phone'=>'string|required',
            'email'=>'string|required',
            'password'=>'string|required',
            'address'=>'string|required',
         
        ]);
      
        $data = $request->all();
        $olduser =\App\Models\User::where('phone',$data['phone'])->get();
        if(count($olduser) > 0)
            return response()->json([
                'success' => false,
                'message' => 'Số điện thoại đã tồn tại!',
            ], 200);
             
        $olduser = \App\Models\User::where('email',$data['email'])->get();
        if(count($olduser) > 0)
                return response()->json([
                    'success' => false,
                    'message' => 'Email đã tồn tại!',
                ], 200);
        $data['photo'] = asset('/storage/avatar/OIP (3) (1)_UtNYV.jpg');
        $data['password'] = Hash::make($data['password']);
        $data['username'] = $data['phone'];
        $data['role'] = 'customer';
        $status = \App\Models\User::c_create($data);
        if(!$status) 
        {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xãy ra!',
            ], 200);
        }    
       
        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công!',
        ], 200);
    }



    //
}

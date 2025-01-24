<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
class IndexController extends  Controller
{
    //
    public function __construct( )
    {
      
    }
    public function getSiteInfo()
    {
        $settings = \DB::select("select * from setting_details");

        $setting = $settings[0];
        $tags = \DB::select("select * from tags where status = 'active' order by hit desc limit 5");
        return response()->json([
            'success' => true,
            'setting' => json_encode($setting),
            'tags' => json_encode($tags),
        ], 200);
        
    }
    public function getSiteInfoPost()
    {
        $settings = \DB::select("select * from setting_details");
        $setting = $settings[0];
        $tags = \DB::select("select * from tags where status = 'active' order by hit desc limit 5");
        $user = auth()->user();
        return response()->json([
            'success' => true,
            'setting' => json_encode($setting),
            'tags' => json_encode($tags),
            'user' => json_encode($user),
        ], 200);
    }
  
}
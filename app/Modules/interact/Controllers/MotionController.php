<?php

namespace App\Modules\interact\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\interact\Models\Motion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MotionController extends Controller
{
    protected $pagesize;
    public function index()
    {
        $func = "interact_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="interact_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Motion </li>';
        $motions=Motion::orderBy('id','DESC')->paginate($this->pagesize);
        return view('interact::motion.index',compact('motions','breadcrumb','active_menu'));
    }

    
}
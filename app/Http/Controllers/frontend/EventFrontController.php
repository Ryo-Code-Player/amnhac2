<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Blog\Models\Blog;
use App\Modules\Event\Models\Event;

class EventFrontController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct( )
    {

    }
    public function index()
    {
        $event = Event::orderBy('created_at', 'desc')->get();

        return view('frontend.event.master', compact('event'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function detail($id)
    {
        $event = Event::orderBy('created_at', 'desc')
            ->where('id', $id)
            ->get();

        return view('frontend.event.detail.master', compact('event'));
    }
}

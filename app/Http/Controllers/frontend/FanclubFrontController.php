<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Blog\Models\Blog;
use App\Modules\Event\Models\Event;
use App\Modules\Fanclub\Models\Fanclub;
use App\Modules\Fanclub\Models\FanclubUser;
use Illuminate\Support\Str;

class FanclubFrontController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct( )
    {

    }
    public function index()
    {

        $fanclubs = Fanclub::where('status','active')
        ->with(relations: 'user')
        ->orderBy('created_at','desc')->get();
        if(auth()->check()){
            $fanclubs_user = FanclubUser::where('user_id',auth()->user()->id)->with('fanclub')->get();
        }else{
            $fanclubs_user = [];
        }
        // dd(1);
        return view('frontend.fanclub.index',compact('fanclubs','fanclubs_user'));
    }

    public function follow(Request $request)
    {
        if($request->fanclub_id){
            $fanclubUser = FanclubUser::where('fanclub_id',$request->fanclub_id)->where('user_id',auth()->user()->id)->first();
            if($fanclubUser){
                $fanclubUser->delete();
                $fanclub = Fanclub::find($request->fanclub_id);
                $fanclub->quantity--;
                $fanclub->save();
                return response()->json([
                    'status' => 'false',
                    'message' => 'Hủy quan tâm thành công!']);
            }else{
                $fanclub = Fanclub::find($request->fanclub_id);
                $fanclub->quantity++;
                $fanclub->save();
                FanclubUser::create([
                'fanclub_id' => $request->fanclub_id,
                'user_id' => auth()->user()->id,
                ]);
                return response()->json([
                    'status' => 'true',
                    'message' => 'Tăng số lượt quan tâm thành công!']);
            }
        }else{
            return response()->json(['message' => 'Không tìm thấy fanclub!']);
        }
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
        $imagePath = null;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images/fanclub'), $imageName);
            $imagePath = asset('images/fanclub/' . $imageName);
        }else{
            $imagePath = null;
        }

        Fanclub::create([
            'title' => $request->name,
            'slug' => Str::slug($request->name),
            'photo' => $imagePath,
            'summary' => $request->summary,
            'content' => $request->content,
            'user_id' => auth()->user()->id,
            'quantity' => 0,
        ]);
        return response()->json(['message' => 'Tạo fanclub thành công!']);
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

    public function get(string $slug)
    {
        // dd(1);
        if(!auth()->check()){
            return redirect()->back();
        }
        
        $fanclub = Fanclub::where('slug',$slug)->with('events')->first();

        $fanclub_user = FanclubUser::where('fanclub_id',$fanclub->id)->where('user_id',auth()->user()->id)->first();
        if($fanclub_user){
            return view('frontend.fanclub.get',compact('fanclub'));
        }else{
            if(auth()->user()->id == $fanclub->user_id){
                return view('frontend.fanclub.get',compact('fanclub'));
            }else{
                return redirect()->back();
            }
        }
        return view('frontend.fanclub.get',compact('fanclub'));


        // dd($fanclub);
    }

    public function eventCreate(string $slug)
    {
        // dd($slug);
        // dd(1);
        return view('frontend.fanclub.event-create',compact('slug'));
    }

    public function eventStore(Request $request)
    {
        // dd($request->all());
        $imagePath = null;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images/event'), $imageName);
            $imagePath = asset('images/event/' . $imageName);
        }else{
            $imagePath = null;
        }
        Event::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'fanclub_id' => $request->fanclub_id,
            'diadiem' => $request->location,
            'photo' => $imagePath,
            'timestart' => $request->timestart,
            'timeend' => $request->timeend,
        ]);
        $fanclub = Fanclub::find($request->fanclub_id);
        // dd(1);
        return redirect()->route('front.fanclub.get',['slug' => $fanclub->slug])->with('success','Tạo sự kiện thành công!');
    }

    public function eventDelete(string $id)
    {
        $event = Event::find($id);
        $event->delete();
        return redirect()->back()->with('success','Xóa sự kiện thành công!');
        // return redirect()->route('front.fanclub.get',['slug' => $event->fanclub->slug])->with('success','Xóa sự kiện thành công!');
    }

    public function eventDetail(string $slug)
    {
        $event = Event::where('slug',$slug)->first();
        return view('frontend.fanclub.event-detail',compact('event'));
    }

    public function delete(string $id)
    {
        $fanclub = Fanclub::find($id);
        $fanclub->delete();
        return response()->json(['message' => 'Xóa fanclub thành công!']);
    }
}

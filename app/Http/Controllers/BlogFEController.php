<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentChildren;
use App\Models\Follow;
use App\Models\ImageUser;
use App\Models\Post;
use App\Models\PostUser;
use App\Models\ReplyComment;
use App\Models\User;
use App\Modules\MusicCompany\Models\MusicCompany;
use App\Modules\MusicType\Models\MusicType;
use App\Modules\Resource\Models\Resource;
use App\Modules\Singer\Models\Singer;
use App\Modules\Song\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BlogFEController extends Controller
{
    //
    public function index(Request $request)
    {
        if(!auth()->check()){
            return redirect('/');
        }
        if($request->id){
            $user = User::where('id', $request->id)->first();
            $posts = Post::with('user','postUser','commentUser.user','commentUser.commentChildrenUser.user','commentUser.replyComment.user')->where('status', 1)->orderBy('created_at', 'desc')->get();
           
            $post_user =  Post::with('user','postUser','commentUser.user','commentUser.commentChildrenUser.user','commentUser.replyComment.user')
            ->where('user_id', $request->id)->orderBy('created_at', 'desc')->get();
            $image_user = ImageUser::where('user_id', $request->id)->get();
            $follow = Follow::where('user_id', $request->id)->with('userFollow')->get();
            $following = Follow::where('user_follow', $request->id)->with('userFollow')->get();
            $singer = Singer::where('user_id', $request->id)->orderBy('created_at', 'desc')
            ->with('company','musicType')
            ->get();
        
           
        }
        $music_type = MusicType::where('status','active')->get();
        $company = MusicCompany::where('status','active')->get();
        $user_id = auth()->user()->id;
        
        $allSongs = collect();
        foreach($singer as $s){
            $allSongs = $allSongs->merge(Song::where('singer_id', $s->id)->with('singer')->get());
        }
        $allSongs2 = Song::where('user_id', $user_id)->get();
        $allSongs = $allSongs->merge($allSongs2);
        // dd($allSongs);die;
        return view('frontend.blog.index', 
        compact('posts','post_user','image_user','user',
        'follow','following','music_type','company','singer','allSongs'));
    }

    // public function indexSlug($slug)
  
    public function getComments(Request $request)
    {
        $posts = Post::with('user','postUser','commentUser.user','commentUser.commentChildrenUser.user','commentUser.replyComment.user')
        ->where('id', $request->post_id)
        ->where('status', 1)->orderBy('created_at', 'desc')->first();
        return response()->json([
            'posts' => $posts
        ], 200);
    }
    public function post(Request $request)
    {
      
        $image = null;
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension(); 
            $filename = 'avatar_' . time() . '.' . $extension; 
            $path = $request->file('image')->storeAs('posts', $filename, 'public');
            $image = asset('storage/' . $path); 
        }
        // dd($image);
        Post::create([
            'description' => $request->content,
            'music_links' => $request->music_links ?? null,
            'image' => $image,
            'user_id' => auth()->user()->id,
            'like' => 0,
            'dislike' => 0,
            'comment' => 0,
            'share' => 0,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'link' => count(json_decode($request->music_links)) > 0 ? json_decode($request->music_links)[0] : null,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Bài viết đã được tạo thành công',
        ], 200);
        
    }

    public function like(Request $request)
    {
        $postUser = PostUser::where('post_id', $request->post_id)
        ->where('user_id', auth()->user()->id)->first();
        if($postUser){
            $postUser->delete();
            $post = Post::where('id', $request->post_id)->first();
            $post->like--;
            $post->save();
            return response()->json([
                'success' => false,
                'message' => 'Bài viết đã hủy like',
            ], 400);
        }else{
            PostUser::create([
                'post_id' => $request->post_id,
                'user_id' => auth()->user()->id,
                'status' => $request->status,
            ]);
            $post = Post::where('id', $request->post_id)->first();
          
            $post->like++;
            $post->save();
           
        }
        return response()->json([
            'success' => true,
            'message' => 'Bài viết đã được like',
        ], 200);
    }

    public function comment(Request $request)
    {
        if(isset($request->comment_id)){
            $comment = Comment::findOrFail($request->comment_id);
            $comment->update([
                'content' => $request->comment_content,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Chỉnh sửa bình luận thành công',
            ], 200);
        }
        $comment = Comment::create([
            'post_id' => $request->post_id,
            'user_id' => auth()->user()->id,
            'content' => $request->content,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'like' => 0,
            'reply' => 0,
        ]);
        $post = Post::where('id', $request->post_id)->first();
        $post->comment++;
        $post->save();
        return response()->json([
            'data' => $comment->load('user'),
            'success' => true,
            'message' => 'Bình luận đã được tạo thành công',
        ], 200);
    }

    public function delete(Request $request)
    {
        $post = Post::where('id', $request->post_id)->first();
        $post->delete();
        return response()->json([
            'success' => true,
            'message' => 'Bài viết đã được xóa thành công',
        ], 200);
    }

    public function edit(Request $request)
    {
       
        $post = Post::where('id', $request->post_id)->first();
        $post->description = $request->description;
        $post->link = count(json_decode($request->music_links)) > 0 ? json_decode($request->music_links)[0] : null;
     
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension(); 
            $filename = 'avatar_' . time() . '.' . $extension; 
            $path = $request->file('image')->storeAs('posts', $filename, 'public');
            $image = asset('storage/' . $path); 
            $post->image = $image;
        }
        $post->save();
        return response()->json([
            'success' => true,
            'message' => 'Bài viết đã được cập nhật thành công',
        ], 200);
    }


    public function likeComment(Request $request)
    {
        if($request->id){
            $request->merge(['comment_id' => $request->id]);
        }
        $commnet_child = CommentChildren::where('comment_id', $request->comment_id)->where('user_id', auth()->user()->id)->first();
        if($commnet_child){
            $commnet_child->delete();
            $comment = Comment::where('id', $request->comment_id)->first();
            $comment->like--;
            $comment->save();
            return response()->json([
                'success' => false,
                'message' => 'Bình luận đã hủy like',
            ], 290);
        }else{
            CommentChildren::create(attributes: [
                'comment_id' => $request->comment_id,
                'user_id' => auth()->user()->id,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $comment = Comment::where('id', $request->comment_id)->first();
            $comment->like++;
            $comment->save();
            return response()->json([
                'success' => true,
                'message' => 'Bình luận đã được like',
            ], 200);
        }
       

        
    }

    public function deleteComment($id)
    {
       Comment::findOrFail($id)->delete();
       return response()->json([
            'success' => true,
            'message' => 'Bình luận đã bị xoá',
        ], 200);
        
    }

    public function updateProfile(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'Người dùng không tồn tại',
            ], 400);
        }
        $user->full_name = $request->name;
        $user->phone = $request->phone;
        $user->taxaddress = $request->address;
        $user->description = $request->description;
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        if($request->photo)
        {
         
                $filename = $request->file('photo')->getClientOriginalName();

                // Lưu vào storage/app/public/avatars với tên gốc
                $path = $request->file('photo')->storeAs('avatar', $filename);
              
                // Lưu vào database:
                $user->photo = str_replace('public/', 'storage/', $path);

        }
        $user->save();

        if($request->photo){
            $imageUser = ImageUser::create([
                'user_id' => auth()->user()->id,
                'image' => $user->photo,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            ImageUser::where('id', '!=', $imageUser->id)->update([
                'status' => 2,
            ]);            
        }
        return response()->json([
            'success' => true,
            'message' => 'Thông tin cá nhân đã được cập nhật thành công',
        ], 200);
    }

    public function deletePhoto(Request $request)
    {
        $imageUser = ImageUser::where('id', $request->id)->first();
        $imageUser->delete();
        return response()->json([
            'success' => true,
            'message' => 'Ảnh đã được xóa thành công',
        ], 200);
    }

    public function toggleFollow(Request $request){
        $follow = Follow::where('user_id', auth()->user()->id)->where('user_follow', $request->user_id)->first();
        if($follow){
            $monitor = User::where('id', $follow->user_follow)->first();
            $monitor->monitor--;
            $monitor->save();
            $follow->delete();
            $user = User::where('id', auth()->user()->id)->first();
            $user->following--;
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Đã hủy theo dõi',
            ], 200);
        }else{
            $following = Follow::create([
                'user_id' => auth()->user()->id,
                'user_follow' => $request->user_id,
            ]);
            $monitor = User::where('id', $following->user_follow)->first();
            $monitor->monitor++;
            $monitor->save();
            $user = User::where('id', auth()->user()->id)->first();
            $user->following++;
            $user->save();
            return response()->json([
                'following' => $following,
                'success' => true,
                'message' => 'Đã theo dõi',
            ], 200);
        }
    }


    public function singerStore(Request $request)
    {
    
        $validatedData = $request->validate([
            'alias' => 'required',
            'type_id' => 'required',
            'summary' => 'nullable',
            'start_year' => 'nullable',
            'status' => 'required',
            'company_id' => 'required',
            'content' => 'required'
        ]);

        // Tạo slug từ alias
        $validatedData['slug'] = Str::slug($request->alias);
        $validatedData['user_id'] = auth()->user()->id;
        if($request->file('photo'))
        {
            $filename = $request->file('photo')->getClientOriginalName();
            $path = $request->file('photo')->storeAs('avatar', $filename);
            $validatedData['photo'] = asset('storage/'.$path);
            
        }
        $singer = Singer::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Lưu ca sĩ thành công!',
            'data' => $singer
        ], 200);
    }

    public function singerDestroy(Request $request)
    {
        
        $singer = Singer::where('id', $request->id)->first();
        $singer->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa ca sĩ thành công!',
        ], 200);
    }

    public function songStore(Request $request)
    {

            $singer = Singer::where('id', $request->singer_id)->first();
                // if(!$singer){
                //     return response()->json([
                //         'success' => false,
                //         'message' => 'Ca sĩ không tồn tại',
                //     ], 400);
                // }


            if ($request->song_id) {
                // Nếu có song_id → chỉnh sửa
                $resource = Resource::findOrFail($request->resource_id);
                $song = Song::findOrFail($request->song_id);
                $resource->update([
                    'title' => $request->title,
                    'slug' => Str::slug($request->title),
                    'file_type' => 'URL',
                    'file_size' => 0,
                    'url' => $request->resource,
                    'code' => 'songs',
                    'type_code' => 'order',
                ]);
                if($resource){
                    $array = [];
                    $array['song_id'] = $request->singer_id??null;
                    $array['resource_id'] = $resource->id;
                    $check[] = $array;
                    $song->update([
                        'title' => $request->title,
                        'slug' => Str::slug($request->title),
                        'singer_id' => $request->singer_id??null,
                        'summary' => $request->summary,
                        'content' => $request->content,
                        'status' => 'active',
                        'company_id' => $singer->company_id ?? null,
                        'musictype_id' => $singer->musicType->id ?? null,
                        'resources' => json_encode($check),
                    ]);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Chỉnh sửa bài hát thành công!',
                    'data' => $song
                ], 200);
                
            } else {
                // Nếu không có song_id → tạo mới
                $resource = Resource::create([
                    'title' => $request->title,
                    'slug' => Str::slug($request->title),
                    'file_type' => 'URL',
                    'file_size' => 0,
                    'url' => $request->resource,
                    'code' => 'songs',
                    'type_code' => 'order',
                ]);
                if($resource){
                    $array = [];
                    $array['song_id'] = $request->singer_id??null;
                    $array['resource_id'] = $resource->id;
                    $check[] = $array;
                    $song = Song::create([
                        'title' => $request->title,
                        'slug' => Str::slug($request->title),
                        'singer_id' => $request->singer_id??null,
                        'summary' => $request->summary,
                        'content' => $request->content,
                        'status' => 'active',
                        'company_id' => $singer->company_id ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'musictype_id' => $singer->musicType->id ?? null,
                        'resources' => json_encode($check),
                        'user_id' => $request->singer_id ? null : auth()->user()->id,
                    ]);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Lưu bài hát thành công!',
                    'data' => $song
                ], 200);
            }
    }

    public function songDestroy($id)
    {
      
        $song = Song::where('id', $id)->first();
        $song->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa bài hát thành công!',
        ], 200);
    }

    public function sharePost(Request $request)
    {
    
        $post = Post::where('id', $request->id)->first();
        $post->share++;
        $post->save();

        Post::create([
            'description' => $post->description,
            'music_links' => $post->music_links ?? null,
            'image' => $post->image,
            'user_id' => auth()->user()->id,
            'like' => 0,
            'dislike' => 0,
            'comment' => 0,
            'share' => 0,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'link' => $post->link ?? null,
            'post_form' => $post->id
         ]);
        return response()->json([
            'success' => true,
            'message' => 'Chia sẻ thành công!',
        ], 200);
    }

    public function replyComment(Request $request){
      
        ReplyComment::create([
            'comment_id' => $request->comment_id,
            'user_id' => auth()->user()->id,
            'content' => $request->content,
            'created_at' => now(),
            'updated_at' => now(),
            'like' => 0,
            'comment_id' => $request->comment_id,
        ]);
        $comment = Comment::where('id', $request->comment_id)->first();
        $comment->reply++;
        $comment->save();
        return response()->json([
            'success' => true,
            'message' => 'Trả lời bình luận thành công!',
        ], 200);
    }
}

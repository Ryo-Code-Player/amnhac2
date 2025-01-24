<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Blog;
use App\Models\Tag;
use App\Models\User;
use App\Modules\Tuongtac\Models\TPage;
use App\Modules\Tuongtac\Models\TBlog;
use App\Modules\Tuongtac\Models\TPageItem;
use App\Modules\Resource\Models\Resource;
use Illuminate\Support\Facades\Validator;
use  App\Modules\Tuongtac\Models\TComment;
use  App\Modules\Tuongtac\Models\TNotice;
 
use  App\Modules\Tuongtac\Models\TTag;
use  App\Modules\Tuongtac\Models\TTagItem;
use  App\Modules\Tuongtac\Models\TMotion;
use  App\Modules\Tuongtac\Models\TMotionItem;
use  App\Modules\Tuongtac\Models\TRecommend;
use  App\Modules\Tuongtac\Models\TVoteItem;
 
class BlogController extends Controller
{
    //
    public function getblog(Request $request)
{
    $this->validate($request, [
        'slug' => 'string|nullable',
        'id' => 'integer|nullable',
    ]);

    // Lấy bài viết theo slug hoặc id
    $blog = null;
    if ($request->slug) {
        $blog = Blog::where('slug', $request->slug)->first();
    }
    if ($request->id) {
        $blog = Blog::find($request->id);
    }

    if (!$blog) {
        return response()->json([
            'success' => false,
            'message' => 'Bài viết không tồn tại.',
        ], 404);
    }

    // Lấy thông tin tác giả
    $author = User::find($blog->user_id);
    $blog->author_name = $author->full_name ?? null;
    $blog->author_photo = $author->photo ?? null;
    $blog->author_id = $author->id ?? null;

    // Dữ liệu tương tác (giả lập)
    $tuongtac = [
        'isBookmarked' => false, // Mặc định chưa bookmark
        'countBookmarked' => 0, // Số lượt bookmark (giả lập là 0)
        'reactions' => [], // Không có thông tin tương tác
        'hasComment' => 0, // Mặc định không có bình luận
        'comments' => [], // Không tải bình luận
        'voteRecord' => null, // Không có thông tin vote
    ];

    // Trả về thông tin bài viết và dữ liệu tương tác
    return response()->json([
        'success' => true,
        'blog' => $blog,
        'tuongtac' => $tuongtac,
    ], 200);
}

public function getBlogCat(Request $request)
{
    $this->validate($request, [
        'catId' => 'integer|nullable',
        'tag' => 'string|nullable',
        'page' => 'integer|required',
        'limit' => 'integer|required',
    ]);

    // Lấy tham số từ request
    $catId = $request->input('catId');
    $tag = $request->input('tag');
    $page = $request->input('page');
    $limit = $request->input('limit');
    $offset = ($page - 1) * $limit;

    $posts = collect(); // Danh sách bài viết
    $total = 0; // Tổng số bài viết

    if ($tag) {
        // Tìm tag trong bảng tags
        $tagModel = Tag::where('title', $tag)->first();

        if ($tagModel) {
            // Truy vấn bài viết liên quan đến tag thông qua cột tags JSON
            $posts = DB::table('blogs')
                ->whereJsonContains('tags', (string)$tagModel->id) // Giải mã JSON
                ->select(
                    'blogs.id',
                    'blogs.title',
                    'blogs.photo',
                    'blogs.summary',
                    'blogs.slug',
                    'blogs.created_at',
                    'blogs.tags'
                )
                ->orderBy('blogs.id', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get();

            // Đếm tổng số bài viết
            $total = DB::table('blogs')
                ->whereJsonContains('tags', (string)$tagModel->id)
                ->count();

            // Ánh xạ tags từ JSON sang tiêu đề tags
            $posts = $posts->map(function ($post) {
                $tagIds = json_decode($post->tags, true); // Giải mã cột tags từ JSON
                $post->tags = Tag::whereIn('id', $tagIds)->pluck('title')->toArray(); // Lấy danh sách tiêu đề tags
                return $post;
            });
        }
    }

    if ($catId) {
        // Truy vấn bài viết theo danh mục
        $posts = DB::table('blogs')
            ->select(
                'id',
                'title',
                'photo',
                'summary',
                'slug',
                'created_at',
                'tags'
            )
            ->where('cat_id', $catId)
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        // Đếm tổng số bài viết theo danh mục
        $total = DB::table('blogs')
            ->where('cat_id', $catId)
            ->count();

        // Ánh xạ tags từ JSON sang tiêu đề tags
        $posts = $posts->map(function ($post) {
            $tagIds = json_decode($post->tags, true); // Giải mã cột tags từ JSON
            $post->tags = Tag::whereIn('id', $tagIds)->pluck('title')->toArray(); // Lấy danh sách tiêu đề tags
            return $post;
        });
    }

    // Trả về dữ liệu JSON
    return response()->json([
        'data' => $posts,
        'current_page' => $page,
        'per_page' => $limit,
        'total' => $total,
        'total_pages' => ceil($total / $limit),
    ]);
}

    public function getBlogSearch(Request $request)
    {
        $this->validate($request, [
            'search' => 'string|nullable',
           
            'page' => 'integer|required',
            'limit' => 'integer|required',
        ]);

        // Lấy tham số từ request
        $searchdata =$request->search;
        $sdatas = explode(" ",$searchdata);
        $searchdata = implode("%", $sdatas);
        $page = $request->input('page');
        $limit = $request->input('limit');
        $offset = ($page - 1) * $limit;
        $posts = DB::table('blogs')
        ->select('id', 'title', 'photo', 'summary','slug','created_at')
        ->where('title','like', '%'.$searchdata.'%')
        ->orWhere('summary','like', '%'.$searchdata.'%')
        ->orderBy('id', 'desc')
        ->offset($offset)
        ->limit($limit)
        ->get();

        $tempposts = DB::table('blogs')
        ->select('id', 'title', 'photo', 'summary','slug','created_at')
        ->where('title','like', '%'.$searchdata.'%')
        ->orWhere('summary','like', '%'.$searchdata.'%')
        ->orderBy('id', 'desc')
        ->get();
        // Truy vấn dữ liệu với phân trang
      
        // Đếm tổng số bài viết trong danh mục
        $total = count($tempposts);
        // Trả về kết quả dưới dạng JSON
        return response()->json([
            'data' => $posts,
            'current_page' => $page,
            'per_page' => $limit,
            'total' => $total,
            'total_pages' => ceil($total / $limit),
        ]);
     
    }
    public function store_book(Request $request)
    {
       
        set_time_limit(6000);
        $func = "blog_add";
        if(!$this->check_function($func))
        {
            return response()->json([
                'success' => false,
                'message' => 'Lưu thất bại!2',
            ], 200);
        }
        //
        $this->validate($request,[
            'title'=>'string|required',
            'content'=>'string|required',
            'tag_ids'=>'string|nullable',
            'urls'=>'string|nullable',
        ]);
        $tag_ids = $request->tag_ids;
       
        $data = $request->all();
        $helpController = new \App\Http\Controllers\HelpController();
        $fileController = new \App\Http\Controllers\FilesController();
          /// ------end replace --///
        $slug = Str::slug( $data['title']);
        $slug_count = TBlog::where('slug',$slug)->count();
        if ( $slug_count > 0)
        {
            return response()->json([
                'success' => false,
                'message' => 'Đã có',
            ], 200);
        }
        $data['content'] = $helpController->removeImageStyle( $data['content'] );
        $data['slug'] = $slug;
        
       
            // $data['content'] = $helpController->read_change_content($data['content']  );
        $data['content'] = $helpController->uploadImageInContent( $data['content'] );
        
        // Lấy danh sách tất cả user_id
        $userIds =  User::pluck('id')->toArray();

        // Chọn ngẫu nhiên 1 user_id
        do{
            $randomUserId = $userIds[array_rand($userIds)];
        } while($randomUserId > 824);

        $data['user_id'] =  $randomUserId;
         
        $photo = array();
        
        if($request->photo != null)
        {
            $photo = [$data['photo']];
        }
        $data['photo']=json_encode($photo);
            
        $data['status'] = 1;
        $blog = \App\Modules\Tuongtac\Models\TBlog::create($data);
        $urls = $request->urls;
        if($urls)
        {
            $resourceIds = array();
            $urls = json_decode($urls);
            foreach ($urls as $url)
            {
                if($url)
                    $resourceIds[] = Resource::createUrlResource(uniqid(), $url, 'other','tblog')->id;
            }
            $blog->resources =  $resourceIds;
            $blog->save();
        }
        $tag_ids = json_decode($data['tag_ids']);
        $tagcontroller = new \App\Modules\Tuongtac\Controllers\TTagController();
        $tagcontroller->store_item_tag($blog->id, $tag_ids,'tblog');
        $author = User::find($data['user_id']) ;
        if($author)
        {
            $page = TPage::where('item_id',$author->id)->where('item_code','user')->first();
            if (!$page)
            {
                $slug = Str::slug($author->full_name);
                $ppage = TPage::where('slug',$slug)->first();
                if($ppage)
                {
                    $slug .= uniqid();
                }
                $datai['item_id'] = $author->id;
                $datai['item_code'] = 'user';
                $datai['title'] = $author->full_name;
                $datai['slug'] = $slug;
                $datai['description'] ="";
                $datai['banner'] = "https://itcctv.vn/images/profile-8.jpg";
                $datai['avatar'] = $author->photo?$author->photo:"https://itcctv.vn/images/profile-8.jpg";
                $datai['status'] = "active";
                $page = TPage::create($datai);
            }
            $datam['item_id']= $blog->id;
            $datam['item_code']= 'tblog';
            $datam['page_id']= $page->id;
            $item = TPageItem::create($datam);
            $item->order_id = $item->id;
            $item->save();
        }
       
        if($blog){
            return response()->json([
                'success' => true,
                'message' => 'Đã lưu thành công!',
            ], 200);
        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'Lưu thất bại!3',
            ], 200);
        }    
    }


    public function store(Request $request)
    {
         
        set_time_limit(6000);
        $func = "blog_add";
        if(!$this->check_function($func))
        {
            return response()->json([
                'success' => false,
                'message' => 'Lưu thất bại!2',
            ], 200);
        }
        //
        $this->validate($request,[
            'title'=>'string|required',
            'photo'=>'string|nullable',
            'summary'=>'string|nullable',
            'content'=>'string|required',
            'photo'=>'string|nullable',
            'tag_ids'=>'string|nullable',
            'blogtype'=>'string|nullable',
            
        ]);
        $tag_ids = $request->tag_ids;
       
        $data = $request->all();
        $helpController = new \App\Http\Controllers\HelpController();
        $fileController = new \App\Http\Controllers\FilesController();
          /// ------end replace --///
         
          $slug = Str::slug($request->input('title'));
          $slug_count = Blog::where('slug',$slug)->count();
          if ( $slug_count > 0)
          {
            return response()->json([
                'success' => false,
                'message' => 'Bài viết đã có',
            ], 200);
          }
          $data['title'] = $helpController->change_title($data['title']);
          $slug = Str::slug( $data['title']);
          $slug_count = Blog::where('slug',$slug)->count();
          if ( $slug_count > 0)
          {
            return response()->json([
                'success' => false,
                'message' => 'Bài viết đã có',
            ], 200);
          }
          
         
         
           
          $data['content'] = $helpController->removeImageStyle( $data['content'] );
          // ------end replace --///
       
      
        $data['slug'] = $slug;
        if($request->photo == null || $request->photo == 'null' ||$request->photo == '')
            $data['photo'] = asset('storage/avatar/165524363_10157953497362187_6637955158143341986_n_BuqbH.jpg');
        else
            $data['photo']= $fileController->blogimageUpload( $data['photo']);
        $data['user_id'] = auth()->user()->id;
       
            // $data['content'] = $helpController->read_change_content($data['content']  );
        $data['content'] = $helpController->uploadImageInContent( $data['content'] );
        // $data['summary'] = $helpController->removeatag($data['summary'],'iframe' );
        // $data['summary'] =  strip_tags($data['summary']);
        // Lấy danh sách tất cả user_id
        $userIds =  User::pluck('id')->toArray();

        // Chọn ngẫu nhiên 1 user_id
        do{
            $randomUserId = $userIds[array_rand($userIds)];
        } while($randomUserId > 824);
        $data['user_id'] =  $randomUserId;
        if($request->blogtype=="blog" )
        {
         

            // if($data['cat_id'] == 6)
            // {
            //     $data['title'].=' Macos App';
            // }
            // if ($data['summary'] && $data['summary']!= '')
            //     $data['summary'] = $helpController->change_content($data['summary']  );
       
            $blog = Blog::create($data);
            $tagcontroller = new \App\Http\Controllers\TagController();
            $tag_ids = json_decode($data['tag_ids']);
            
            $tagcontroller->store_blog_tag($blog->id, $tag_ids);
        }
        else
        {
            $photo = array();
           
            
            $photo = [$data['photo']];
            
            $data['photo']=json_encode($photo);
            
            $data['status'] = 1;
            $blog = \App\Modules\Tuongtac\Models\TBlog::create($data);
            if (!isset($data['tag_ids']))
                $tag_ids = [$data['cat_id']];
            else
                $tag_ids = json_decode($data['tag_ids']);
            $tagcontroller = new \App\Modules\Tuongtac\Controllers\TTagController();
            $tagcontroller->store_item_tag($blog->id, $tag_ids,'tblog');

            $author = User::find($data['user_id']) ;
            if($author)
            {
                $page = TPage::where('item_id',$author->id)->where('item_code','user')->first();
                if (!$page)
                {
                    $slug = Str::slug($author->full_name);
                    $ppage = TPage::where('slug',$slug)->first();
                    if($ppage)
                    {
                        $slug .= uniqid();
                    }
                    $datai['item_id'] = $author->id;
                    $datai['item_code'] = 'user';
                    $datai['title'] = $author->full_name;
                    $datai['slug'] = $slug;
                    $datai['description'] ="";
                    $datai['banner'] = "https://amnhac.cuoituan.vn/images/profile-8.jpg";
                    $datai['avatar'] = $author->photo?$author->photo:"https://amnhac.cuoituan.vn/images/profile-8.jpg";
                    $datai['status'] = "active";
                    $page = TPage::create($datai);
                }
                $datam['item_id']= $blog->id;
                $datam['item_code']= 'tblog';
                $datam['page_id']= $page->id;
                $item = TPageItem::create($datam);
                $item->order_id = $item->id;
                $item->save();
            }
        }
        
       
        if($blog){
            return response()->json([
                'success' => true,
                'message' => 'Đã lưu thành công!',
            ], 200);
        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'Lưu thất bại!3',
            ], 200);
        }    
    }


    public function update_42($id,$cat)
    {
        set_time_limit(6000);
        $bblogs =   \App\Models\BotNews::where('url_id',$id)->where('base','=','')->where('isdelete',0)
            ->where('photo','<>','')->orderBy('id','desc')->get();
        
        // \DB::select($sql);
        $helpController = new \App\Http\Controllers\HelpController();
        $fileController = new \App\Http\Controllers\FilesController();
        $n = 1;
        $size_n = count($bblogs);
        foreach ($bblogs as $bblog)
        {
            $btitle = $bblog->base;
            if ($btitle == null || $btitle == '')
                $btitle = $bblog->title;
            $blogs = \App\Models\Blog::where('title','like','%'.$btitle)->get();
            echo '<br/>'.$n.'/'.$size_n.'<br/>count: '.count($blogs);
            if(count($blogs)==0  )
            {
                $n ++;
                echo '<br/>TITLE: '. $bblog->title ;
                $data['content'] = $bblog->content;
                $data['content'] = $helpController->removeHrefA($data['content'] );
                $data['content'] = $helpController->removeatag($data['content'],'iframe' );
                $data['content'] = $helpController->removeatag( $data['content'],'noscript' );
                $data['content'] = $helpController->remove_all_class($data['content'] );
                $data['content'] = $helpController->changeDataLazySrctoSrc($data['content'] );
                $data['content'] = $helpController->uploadImageInContent( $data['content']);
                $data['content'] = $helpController->removeAllatributeNSS($data['content'] );
                $data['content'] = $helpController->addImagetitle( $data['content'] ,$bblog->title );
                $data['content'] = str_replace('tandoanh.vn','',$data['content']);
                $data['content'] = str_replace('tandoanh','',$data['content']);
                $data['content'] = str_replace('Tân Doanh','',$data['content']);
                $data['content'] = $helpController->removeTagstyle($data['content'],'a','color: #ff0000;' );
                $data['content'] = str_replace('>> Xem thêm các bài viết liên quan:','',$data['content']);
                $data['content'] = str_replace('>> Xem ngay bài viết:','',$data['content']);
                $data['content'] = str_replace('Xem thêm','',$data['content']);
                $data['content'] = $helpController->removeAllatributeATag($data['content'],'figure'  );
                    
                $data['summary'] = $bblog->summary;
                
                // }
                $data['user_id']= auth()->user()->id; 
            //    echo $data['description'];
              
                if($bblog->photo == null)
                    $data['photo'] = asset('storage/avatar/165524363_10157953497362187_6637955158143341986_n_BuqbH.jpg');
                else
                {
                        $photo = $bblog->photo ;
                        if ($photo == "")
                            continue;
                        
                        $uploadedImagePath = $fileController->blogimageUpload( $photo);
                        if($uploadedImagePath!= "")
                            $data['photo'] = $uploadedImagePath ;
                      
                        if ($data['photo'] == '')
                        {
                            continue;
                            echo '<br/>---------------KHONG LOAD DC ANH <br/>';
                        }    
                }
                $data['title'] = $bblog->title;
                $data['title'] = $helpController->change_title($bblog->title);
                $data['cat_id'] = $cat;
                $data['user_id'] = 29;
                $slug = Str::slug($bblog->title);
                $slug_count = \App\Models\Blog::where('slug',$slug)->count();
                if($slug_count > 0)
                {
                    $bblog->base =$data['title'];
                    $bblog->save();
                    continue;
                }
                $data['summary'] = $helpController->removeatag($data['summary'],'iframe' );
                $data['summary'] =  strip_tags($data['summary']);
                $data['summary'] = $helpController->change_content($data['summary']  );
                $data['content'] = $helpController->read_change_content($data['content']  );
               
                $data['slug'] = $slug;
                $blog = \App\Models\Blog::create($data);
                echo '<br/> <br/>TITLE: '.$blog->title;
                $bblog->base =$blog->title; 
                $bblog->save();
                if ($n >= 3)
                    return;
            }
            else
            { 
                // foreach ($blogs as $blog)
                // {
                //     $blog->content = $helpController->removeAllatributeATag($blog->content,'figure'  );
                //     $blog->content = str_replace('>> Xem thêm các bài viết liên quan:','',$blog->content);
                //     $blog->content = str_replace('>> Xem ngay bài viết:','',$blog->content);
                //     $blog->save();
                // }

            }
        }
    }
}

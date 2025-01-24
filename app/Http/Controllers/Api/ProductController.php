<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    //
    public function updateProduct37()
    {
        set_time_limit(2000);
        $sql = "select * from bot_product where url_id =37  and  photo != '' order by id asc";
        $pros = \DB::select($sql);
        $helpController = new \App\Http\Controllers\HelpController();
        $fileController = new \App\Http\Controllers\FilesController();
        $keywords = ['Hikvision','KBVISION', 'IMOU','Ezviz','DAHUA'];
        $n = 1;
        foreach ($pros as $pro)
        {
            $match = 0;
            foreach($keywords as $key)
            {
                $pos = stripos($pro->title, $key);
                if ($pos !== false)
                {
                    $match = 1;
                    echo 'match: '.$pro->title."<br/>";
                }
            }
            if($match == 1)
                continue;
            // sleep( 1);
            $products = \App\Models\Product::where('title','like','%'.'winnfox '. $pro->title)->orderBy('id','asc')->get();
            echo '<br/>count: '.count($products);
            if(count($products)==0  )
            {
                echo '<br/>TITLE: '. $pro->title ;
                // $data['description'] = $pro->description;
                $data['description'] = $helpController->removeHrefA($data['description'] );
                $data['description'] = $helpController->uploadImageInContent( $data['description']    );
                $data['description'] = $helpController->addImagetitle( $data['description'] ,$pro->title );
                $data['description'] =  $data['description']  = str_replace('attribute-item','row',$data['description'] );
                // $data['description']  = str_replace('left','col-lg-4',$data['description'] );
                // $data['description']  = str_replace('right','col-lg-8',$data['description'] );
                        
                $data['summary'] = $pro->summary;
                $data['price'] = $pro->price;
                // }
                $data['user_id']= auth()->user()->id; 
            //    echo $data['description'];
              
                if($pro->photo == null)
                    $data['photo'] = asset('storage/avatar/165524363_10157953497362187_6637955158143341986_n_BuqbH.jpg');
                else
                {
                        $photos = explode( ',', $pro->photo);
                        $newphoto= array();
                        foreach($photos as $photo)
                        {
                            if ($photo == "")
                                continue;
                            $photo = str_replace("!!ma!!", "2 Mã vạch, in HD", $photo);
                            if (strpos($photo, "https:") === false) 
                            {
                                $photo = 'https:'.$photo;
                            }
                                
                            echo ' upload photo https:'. $photo .'<br/>';
                            
                            $uploadedImagePath = $fileController->blogimageUpload( $photo);
                            if($uploadedImagePath!= "")
                            array_push($newphoto,$uploadedImagePath);
                            
                        }
                        $data['photo'] = implode(",",$newphoto);
                      
                        if ($data['photo'] == '')
                        {
                            continue;
                            echo '<br/>---------------KHONG LOAD DC ANH <br/>';
                        }    
                }
                // return;
                $data['brand_id'] = 85;
                $data['stock'] = 0;
                // return $data;
                $data['title'] = 'winnfox '. $pro->title;
                $data['cat_id'] = $this->timcatid($pro->title);
                $proa = \App\Models\Product::c_create($data);
                echo '<br/> <br/>TITLE: '.$proa->title;
                $data1['product_id'] = $proa->id;
                $data1['old_price'] = 0;
                $data1['surl'] = $pro->url;
                \App\Models\Productextend::create($data1);
                
                //  return;
            }
            else
            { 
                $i = 0;
                foreach ($products as $product)
                {
                    $i ++;
                    
                    $product->summary = preg_replace('/<table.*?>.*?<\/table>/s', '', $product->summary);
                    // echo $product->summary;
                    $product->save();
                    if($i > 1)
                    {
                        $product->delete();
                    }
                    echo '<br/>'.$n;
                    $n++;
                }
            }
        }
    }

    public function store(Request $request)
    {
        
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
            'description'=>'string|required',
            'photo'=>'string|nullable',
            
        ]);
        $tag_ids = $request->tag_ids;
       
        $data = $request->all();
        $pro = \App\Models\Product::where('title','like','%'.$data['title'].'%')->first();
        if($pro)
        {
            return response()->json([
                'success' => false,
                'message' => 'Bài viết đã có',
            ], 200);
        }
        $helpController = new \App\Http\Controllers\HelpController();
        $fileController = new \App\Http\Controllers\FilesController();
          /// ------end replace --///
         
          $slug = Str::slug($request->input('title'));
          $slug_count = \App\Models\Product::where('slug',$slug)->count();
          if ( $slug_count > 0)
          {
            return response()->json([
                'success' => false,
                'message' => 'Bài viết đã có',
            ], 200);
          }
         
           
          $data['description'] = $helpController->removeImageStyle( $data['description'] );
          $data['description'] = $helpController->removeHrefA($data['description'] );
        
          $data['description'] = $helpController->addImagetitle( $data['description'] ,$pro->title );
         
          // ------end replace --///
       
      
        $data['slug'] = $slug;
        if($request->photo == null)
            $data['photo'] = asset('storage/avatar/165524363_10157953497362187_6637955158143341986_n_BuqbH.jpg');
        else
        {
            // $data['photo']= $fileController->blogimageUpload( $data['photo']);
            $photos = explode( ',', $data['photo']);
            $newphoto= array();
            foreach($photos as $photo)
            {
                if ($photo == "")
                    continue;
                $photo = str_replace("!!ma!!", "2 Mã vạch, in HD", $photo);
                if (strpos($photo, "http") === false) 
                {
                    $photo = 'https:'.$photo;
                }
                if (strpos($photo, "http:") === true) 
                {
                    $photo = str_replace('http:','https:',$photo);
                }   
               
                
                $uploadedImagePath = $fileController->upload_image_url( $photo);
                echo ' upload photo :'. $uploadedImagePath .'<br/>';
                if($uploadedImagePath!= "")
                    array_push($newphoto,$uploadedImagePath);
            }
            $data['photo'] = implode(",",$newphoto);
        }  
        $setting =  new \App\Http\Controllers\SettingController();  
       
        $data['description'] = $helpController->uploadImageInContent2( $data['description'] ,$data['title']);
        $data['cat_id'] =  $setting->timcatid($data['title']);
        if($data['brand_id'] == 0)
        {
            $data['brand_id'] =  $setting->timbrandid($data['title']);
        }
        $proa = \App\Models\Product::c_create($data);   
        if($proa){
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
    public function getProductBrand(Request $request)
    {
     
        $this->validate($request,[
            'brand_id'=>'numeric|required',
        ]);
        $brand = \App\Models\Brand::find($request->brand_id);
        if(!$brand)
        {
            return response()->json([
                'success' => false,
                'message' => 'Không có danh mục này!',
            ], 200);
        }
        $products = \App\Models\Product::where('brand_id',$brand->id)->where('status','active')->get();
        return response()->json([
            'success' => true,
            'products' => json_encode($products),
        ], 200);
         
    }
    public function getBrand(Request $request)
    {
        $brands = \App\Models\Brand::where('status','active')->get() ;
          return response()->json([
            'success' => true,
            'brands' => json_encode($brands),
        ], 200);
         
    }
    public function productDetail(Request $request)
    {
        if($request->id)
        {
            $id =$request->id;
            $product = \App\Models\Product::find($id);
            if($product)
            {
                $tags = \DB::select("select b.* from (select * from tag_products where product_id = ".$product->id .") as a left join tags as b on a.tag_id = b.id ");
                $product->tag = $tags;
                return response()->json([
                    'success' => true,
                    'product' => json_encode($product),
                ], 200);
            }
            else
            {
                return response()->json([
                    'success' => false,
                    'msg' => 'không tìm thấy',
                ], 200);
            }
           
            
        }
        else
        {
            return response()->json([
                'success' => false,
                'msg' => 'không tìm thấy',
            ], 200);
        }

    }

    public function productJSearch(Request $request)
    {
        if($request->searchdata)
        {
            $searchdata =$request->searchdata;
            // return $searchdata;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);
          
            $products = DB::table('products')->select('products.id','products.title','products.photo')->where(function($query_sub)  use( $searchdata) {
                $query_sub->where('title','LIKE','%'.$searchdata.'%');
            })->where('status','active')->limit(100)->get(); 
            return response()->json([
                'success' => true,
                'products' => json_encode($products),
            ], 200);
            
        }
        else
        {
            return response()->json([
                'success' => false,
                'msg' => 'Khồng tìm thấy dữ liệu',
            ], 200);
        }

    }
   
}

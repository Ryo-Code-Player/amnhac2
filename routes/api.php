<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::post('login', [\App\Http\Controllers\Api\AuthenticationController::class, 'store']);
    Route::post('logout', [\App\Http\Controllers\Api\AuthenticationController::class, 'destroy'])->middleware('auth:api');
    
    Route::post('get_product_brand', [\App\Http\Controllers\Api\ProductController::class, 'getProductBrand'])->middleware('auth:api');
    Route::post('get_brand', [\App\Http\Controllers\Api\ProductController::class, 'getBrand'])->middleware('auth:api');
    Route::post('store_invoice', [\App\Http\Controllers\Api\AgencyController::class, 'store_invoice'])->middleware('auth:api');
    Route::post('get_invoice', [\App\Http\Controllers\Api\AgencyController::class, 'getInvoice'])->middleware('auth:api');
    Route::post('luusanpham', [\App\Http\Controllers\Api\ProductController::class, 'store'])->middleware('auth:api');
    Route::post('get_product_jsearch', [\App\Http\Controllers\Api\ProductController::class, 'productJSearch'])->middleware('auth:api');
    Route::post('get_product_detail', [\App\Http\Controllers\Api\ProductController::class, 'productDetail'])->middleware('auth:api');
    
    Route::post('luubai1', [\App\Http\Controllers\Api\BlogController::class, 'store_book'])->middleware('auth:api');
    Route::post('luubai2', [\App\Http\Controllers\Api\BlogController::class, 'store'])->middleware('auth:api');

    Route::get('blog', [\App\Http\Controllers\Api\BlogController::class, 'getblog']) ;
    Route::get('blogcat', [\App\Http\Controllers\Api\BlogController::class, 'getBlogCat']) ;
    Route::get('blogsearch', [\App\Http\Controllers\Api\BlogController::class, 'getBlogSearch']) ;

    Route::post('register', [\App\Http\Controllers\Api\AuthenticationController::class, 'saveNewUser']);
    Route::post('updateprofile', [\App\Http\Controllers\Api\ProfileController::class, 'updateProfile'])->middleware('auth:api');
    Route::post('updateavatar', [\App\Http\Controllers\Api\ProfileController::class, 'updateAvatar'])->middleware('auth:api');
    Route::get('getsitesetting', [\App\Http\Controllers\Api\IndexController::class, 'getSiteInfo']) ;
    Route::post('getsitesetting', [\App\Http\Controllers\Api\IndexController::class, 'getSiteInfoPost'])->middleware('auth:api');
    Route::post('login/google', [\App\Http\Controllers\Api\AuthenticationController::class, 'loginWithGoogleToken']);
    Route::post('login/facebook', [\App\Http\Controllers\Api\AuthenticationController::class, 'loginWithFacebookToken']);

    Route::get('song', [\App\Http\Controllers\Api\SongController::class, 'getsong']) ;
    Route::get('/resources/{id}', [\App\Http\Controllers\Api\ResourcesController::class, 'getresources']);
    Route::get('tag', [\App\Http\Controllers\Api\TagController::class, 'gettag']) ;
  });
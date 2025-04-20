<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Tuongtac\Controllers\TCommentController;
use App\Modules\Tuongtac\Controllers\TMotionItemController;

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/admin', function () {
//     //xuly 
//     return view('backend.index');
// });
Route::get('/admin/login',[ \App\Http\Controllers\Auth\LoginController::class,'viewlogin'])->name('admin.login');
Route::post('/admin/login',[ \App\Http\Controllers\Auth\LoginController::class,'login'])->name('admin.checklogin');
 
Route::group( ['prefix'=>'admin/','middleware'=>'admin.auth', 'as'=>'admin.'],function(){
    Route::get('/',[ \App\Http\Controllers\AdminController::class,'index'])->name('home');
    Route::post('/logout',[ \App\Http\Controllers\Auth\LoginController::class,'logout'])->name('logout');
   
    Route::get('/dasboard', [App\Http\Controllers\AdminController::class, 'index'])->name('dasboard');

    //User section
    Route::resource('user', \App\Http\Controllers\UserController::class);
    Route::post('user_status',[\App\Http\Controllers\UserController::class,'userStatus'])->name('user.status');
    Route::get('user_search',[\App\Http\Controllers\UserController::class,'userSearch'])->name('user.search');
    Route::get('user_sort',[\App\Http\Controllers\UserController::class,'userSort'])->name('user.sort');
    Route::post('user_detail',[\App\Http\Controllers\UserController::class,'userDetail'])->name('user.detail');
    Route::post('user_profile',[\App\Http\Controllers\UserController::class,'userUpdateProfile'])->name('user.profileupdate');
    Route::get('user_profile',[\App\Http\Controllers\UserController::class,'userViewProfile'])->name('user.profileview');
   ///UGroup section
   Route::resource('ugroup', \App\Http\Controllers\UGroupController::class);
   Route::post('ugroup_status',[\App\Http\Controllers\UGroupController::class,'ugroupStatus'])->name('ugroup.status');
   Route::get('ugroup_search',[\App\Http\Controllers\UGroupController::class,'ugroupSearch'])->name('ugroup.search');

  ///Role section
  Route::resource('role', \App\Http\Controllers\RoleController::class);
  Route::post('role_status',[\App\Http\Controllers\RoleController::class,'roleStatus'])->name('role.status');
  Route::get('role_search',[\App\Http\Controllers\RoleController::class,'roleSearch'])->name('role.search');
  Route::get('role_function\{id}',[\App\Http\Controllers\RoleController::class,'roleFunction'])->name('role.function');
  Route::get('role_selectall\{id}',[\App\Http\Controllers\RoleController::class,'roleSelectall'])->name('role.selectall');
  
  Route::post('functionstatus',[\App\Http\Controllers\RoleController::class,'roleFucntionStatus'])->name('role.functionstatus');
  
    ///cfunction section
    Route::resource('cmdfunction', \App\Http\Controllers\CFunctionController::class);
    Route::post('cmdfunction_status',[\App\Http\Controllers\CFunctionController::class,'cmdfunctionStatus'])->name('cmdfunction.status');
    Route::get('cmdfunction_search',[\App\Http\Controllers\CFunctionController::class,'cmdfunctionSearch'])->name('cmdfunction.search');
   
    /// Setting  section
    Route::resource('setting', \App\Http\Controllers\SettingController::class);
       
    /////file upload/////////

    Route::post('avatar-upload', [\App\Http\Controllers\FilesController::class, 'avartarUpload' ])->name('upload.avatar');

    Route::post('product-upload', [\App\Http\Controllers\FilesController::class, 'productUpload' ])->name('upload.product');
    Route::post('upload-ckeditor', [\App\Http\Controllers\FilesController::class, 'ckeditorUpload' ])->name('upload.ckeditor');
   
});

Route::group( [ 'as'=>'front.'],function(){
    Route::get('/',[ \App\Http\Controllers\frontend\HomeController::class,'index'])->name('home');
    Route::get('/cate',[ \App\Http\Controllers\frontend\HomeController::class,'cate'])->name('cate');

    Route::get('/singer',[ \App\Http\Controllers\frontend\HomeController::class,'singer'])->name('singer');
    

    // songs
    Route::get('/songs',[ \App\Http\Controllers\frontend\HomeController::class,'song'])->name('song');
    Route::get('/song/{slug}', [App\Http\Controllers\frontend\HomeController::class, 'detail'])->name('song.detail');
    Route::get('/songs/{slug}', [App\Http\Controllers\frontend\SongFrontController::class, 'songByCategory'])->name('song.category');
    Route::get('/singersong/{slug}', [App\Http\Controllers\frontend\SongFrontController::class, 'songBySinger'])->name('song.singer');
    Route::get('/songplaylist/{slug}', [App\Http\Controllers\frontend\SongFrontController::class, 'songByPlaylist'])->name('song.playlist');
    Route::get('/searchsong', [App\Http\Controllers\frontend\SongFrontController::class, 'searchsong'])->name('song.search2');

    Route::get('/topic',[ \App\Http\Controllers\frontend\HomeController::class,'topic'])->name('topic');

    Route::get('/blog',[ \App\Http\Controllers\frontend\BlogPageController::class,'index'])->name('blog');
    Route::get('/blogs/{id}',[ \App\Http\Controllers\frontend\BlogPageController::class,'detail'])->name('blog.detail');
    Route::get('/blogs/{blogId}/comments', [\App\Http\Controllers\frontend\BlogPageController::class, 'loadComments'])->name('blogs.comments');
    Route::post('/comments/store', [TCommentController::class, 'store'])->name('comments.store');
    Route::post('/motions/toggle', [TMotionItemController::class, 'toggle'])->name('motions.toggle');
    Route::post('/motions/check', [TMotionItemController::class, 'check'])->name('motions.check');

    Route::get('/detail_music',[ \App\Http\Controllers\frontend\HomeController::class,'detail_music'])->name('detail_music');

    // user profile
    Route::get('/profile',[ \App\Http\Controllers\frontend\ProfileController::class,'index'])->name('profile');
    
    // user register
    Route::get('register', [App\Http\Controllers\frontend\RegisterController::class, 'index'])->name('user.register');
    Route::post('register/store', [App\Http\Controllers\frontend\RegisterController::class, 'store'])->name('user.register.add');

    // event
    Route::get('/event',[ \App\Http\Controllers\frontend\EventFrontController::class,'index'])->name('event');
    Route::get('/event/detail/{id}',[ \App\Http\Controllers\frontend\EventFrontController::class,'detail'])->name('event.detail');

    // fanclub
    Route::get('/fanclub',[ \App\Http\Controllers\frontend\FanclubFrontController::class,'index'])->name('fanclub');
});
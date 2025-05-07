<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\BlogFEController;
use App\Http\Controllers\CheckoutController;
use App\Modules\Event\Models\Event;
use App\Modules\Event\Models\EventUser;
use Illuminate\Support\Facades\Route;
use App\Modules\Tuongtac\Controllers\TCommentController;
use App\Modules\Tuongtac\Controllers\TMotionItemController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


Route::get('/', function () {
    return view('welcome');
});

Route::post('FE-update-profile-user',[ AdminController::class,'updateProfile123'])->name('FE.updateprofile_user');
Route::get('/admin/login',[ \App\Http\Controllers\Auth\LoginController::class,'viewlogin'])->name('admin.login');
Route::post('register',[ \App\Http\Controllers\Auth\LoginController::class,'register'])->name('user.register');



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

    Route::get('/detail_music',[ \App\Http\Controllers\frontend\HomeController::class,'detail_music'])->name('detail_music');

    // user profile
    Route::get('/profile',[ \App\Http\Controllers\frontend\ProfileController::class,'index'])->name('profile');
    
    // user register
    Route::get('register', [App\Http\Controllers\frontend\RegisterController::class, 'index'])->name('user.register');
    Route::post('register/store', [App\Http\Controllers\frontend\RegisterController::class, 'store'])->name('user.register.add');

    // event
    Route::get('/event',[ \App\Http\Controllers\frontend\EventFrontController::class,'index'])->name('event');
    Route::get('/event/detail/{id}',[ \App\Http\Controllers\frontend\EventFrontController::class,'detail'])->name('event.detail');

    





    Route::get('playlist',[ \App\Http\Controllers\frontend\ProfileController::class,'playlist'])->name('playlist');
    Route::get('playlist-slug/{slug}',[ \App\Http\Controllers\frontend\ProfileController::class,'playlist_slug'])->name('playlist.slug');
    Route::post('playlist/create',[ \App\Http\Controllers\frontend\ProfileController::class,'createPlaylist'])->name('playlist.create');
    Route::get('playlist/delete/{id}',[ \App\Http\Controllers\frontend\ProfileController::class,'deletePlaylist'])->name('playlist.delete');
    Route::post('playlist/{id}/add-song',[ \App\Http\Controllers\frontend\ProfileController::class,'addSongToPlaylist'])->name('playlist.addSong');
    Route::get('/zingchart',[ \App\Http\Controllers\frontend\ProfileController::class,'zingchart'])->name('zingchart');
    Route::get('/miquinn-singer',[ \App\Http\Controllers\frontend\ProfileController::class,'zingsinger'])->name('miquinn-singer');
    Route::get('/zing-singer-slug/{slug}',[ \App\Http\Controllers\frontend\ProfileController::class,'zingsinger_slug'])->name('zingsinger_slug');
    Route::get('/blog-index',[BlogFEController::class,'index'])->name('blog.index');
    Route::post('/blog-post',[BlogFEController::class,'post'])->name('blog.post');
    Route::post('/blog-like',[BlogFEController::class,'like'])->name('blog.like');
    Route::delete('/blog-delete',[BlogFEController::class,'delete'])->name('blog.delete');
    Route::post('/blog-comment',[BlogFEController::class,'comment'])->name('blog.comment');
    Route::post('/blog-edit',[BlogFEController::class,'edit'])->name('blog.edit');
    Route::post('/blog-like-comment',[BlogFEController::class,'likeComment'])->name('blog.likeComment');
    Route::post('/blog-update-profile',[BlogFEController::class,'updateProfile'])->name('profile.updateProfile');
    Route::post('/blog-delete-photo',[BlogFEController::class,'deletePhoto'])->name('profile.deletePhoto');
    Route::post('/blog-reply-comment',[BlogFEController::class,'replyComment'])->name('blog.replyComment');
    Route::post('/blog-toggle-follow',[BlogFEController::class,'toggleFollow'])->name('profile.toggleFollow');
    Route::post('/singer-store',[BlogFEController::class,'singerStore'])->name('singer.store');
    Route::post('/singer-destroy',[BlogFEController::class,'singerDestroy'])->name('singer.destroy');
    Route::post('/song-store',[BlogFEController::class,'songStore'])->name('song.store');
    Route::post('/song-destroy/{id}',[BlogFEController::class,'songDestroy'])->name('song.destroy');
    Route::post('/share-post',[BlogFEController::class,'sharePost'])->name('share.post');
    Route::get('/get-comments',[BlogFEController::class,'getComments'])->name('get.comments');
    // fanclub
    Route::get('/fanclub',[ \App\Http\Controllers\frontend\FanclubFrontController::class,'index'])->name('fanclub');
    Route::post('/fanclub',[ \App\Http\Controllers\frontend\FanclubFrontController::class,'store'])->name('fanclub.store');
    Route::get('/fanclub-get/{slug}',[ \App\Http\Controllers\frontend\FanclubFrontController::class,'get'])->name('fanclub.get');
    Route::post('/fanclub-delete/{id}',[ \App\Http\Controllers\frontend\FanclubFrontController::class,'delete'])->name('fanclub.delete');

    Route::post('/fanclub-follow',[ \App\Http\Controllers\frontend\FanclubFrontController::class,'follow'])->name('fanclub.follow');
    Route::get('/fanclub-event-create/{fanclub}',[ \App\Http\Controllers\frontend\FanclubFrontController::class,'eventCreate'])->name('fanclub.event.create');

    Route::post('/fanclub-event-create',[ \App\Http\Controllers\frontend\FanclubFrontController::class,'eventStore'])->name('fanclub.event.store');
    Route::post('/fanclub-event-delete/{id}',[ \App\Http\Controllers\frontend\FanclubFrontController::class,'eventDelete'])->name('fanclub.event.delete');
    Route::get('/fanclub-event-detail/{event}',[ \App\Http\Controllers\frontend\FanclubFrontController::class,'eventDetail'])->name('fanclub.event.detail');

    Route::post('/update-song-view',[ \App\Http\Controllers\frontend\HomeController::class,'songView'])->name('song.view');

    Route::get('/song-share',[ \App\Http\Controllers\frontend\HomeController::class,'songShare'])->middleware('auth')->name('song.share');

    
Route::post('/create-payment-link', [CheckoutController::class, 'createPaymentLink'])->name('payment.link');

});

Route::get('/cancel.html', function () {
    // return redirect()->back()->with('error','Đã hủy đơn hàng!');
    return redirect('/');
});

Route::get('/success.html/{id}/{user_id}', function ($id, $user_id) {

    $event = Event::find($id);
    $event->quantity--;
    $event->save();
    DB::table('event_users')->insert([
        'code' => Str::upper(Str::random(10)),
        'event_id' => $id,
        'user_id' => $user_id,
        'role_id' => 6,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    return redirect('/');
    // return redirect()->route('front.fanclub.event.detail', $event->slug)->with('success','Đã thanh toán đơn hàng!');
});
Route::get('/api/user/event-registrations',[ \App\Http\Controllers\frontend\HomeController::class,'eventRegistrations'])->name('event.registrations');





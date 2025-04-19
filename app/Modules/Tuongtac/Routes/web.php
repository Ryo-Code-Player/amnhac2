<?php

use Illuminate\Support\Facades\Route;
// Define routes here

// Route::group( [ 'prefix'=>'admin' ,  'as' => 'admin.' ],function(){
//     Route::resource('tblog',[AdminTBlogController::class]);
// });

Route::group( [    'as' => 'front.' ],function(){
    
   
});

use App\Modules\Tuongtac\Controllers\TCommentController;
use App\Modules\Tuongtac\Controllers\TMotionItemController;
use App\Modules\Blog\Controllers\BlogController;

Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');

Route::post('/comments', [TCommentController::class, 'store'])->name('comments.store');

Route::post('/likes/toggle', [TMotionItemController::class, 'toggle'])->name('likes.toggle');

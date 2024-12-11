<?php

use Illuminate\Support\Facades\Route;

// Define routes here
use App\Modules\Fanclub\Controllers\FanclubController;
use App\Modules\Fanclub\Controllers\FanclubItemController;
use App\Modules\Fanclub\Controllers\FanclubUserController;
Route::group( ['prefix'=>'admin/'  , 'as' => 'admin.' ],function(){

    Route::resource('Fanclub',  FanclubController::class);
    Route::get('Fanclub_edit/{id}/edit', [FanclubController::class, 'edit'])->name('admin.Fanclub.edit');
    Route::post('Fanclub_update/{id}', [FanclubController::class, 'update'])->name('admin.Fanclub.update');
    Route::post('Fanclub_destroy/{id}', [FanclubController::class, 'destroy'])->name('admin.Fanclub.destroy');
    Route::post('upload/avatar', [FanclubController::class, 'uploadAvatar'])->name('admin.upload.avatar');

    Route::resource('FanclubItem', FanclubItemController::class);
    Route::get('FanclubItem_edit/{id}/edit', [FanclubItemController::class, 'edit'])->name('admin.FanclubItem.edit');
    Route::post('FanclubItem_update/{id}', [FanclubItemController::class, 'update'])->name('admin.FanclubItem.update');
    Route::post('FanclubItem_destroy/{id}', [FanclubItemController::class, 'destroy'])->name('admin.FanclubItem.destroy');

    Route::resource('FanclubUser', FanclubUserController::class);
    Route::get('FanclubUser_edit/{id}/edit', [FanclubUserController::class, 'edit'])->name('admin.FanclubUser.edit');
    Route::post('FanclubUser_update/{id}', [FanclubUserController::class, 'update'])->name('admin.FanclubUser.update');
    Route::post('FanclubUser_destroy/{id}', [FanclubUserController::class, 'destroy'])->name('admin.FanclubUser.destroy');


});
Route::post('avatar-upload', [\App\Http\Controllers\FilesController::class, 'avartarUpload' ])->name('upload.avatar');
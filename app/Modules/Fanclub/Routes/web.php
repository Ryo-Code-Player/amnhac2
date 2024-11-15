<?php

use Illuminate\Support\Facades\Route;

// Define routes here
use App\Modules\Fanclub\Controllers\FanclubController;
use App\Modules\Fanclub\Controllers\FanclubItemController;
Route::group( ['prefix'=>'admin/'  , 'as' => 'admin.' ],function(){

    Route::resource('Fanclub',  FanclubController::class);
    Route::get('Fanclub_edit/{id}/edit', [FanclubController::class, 'edit'])->name('admin.Fanclub.edit');
    Route::post('Fanclub_update/{id}', [FanclubController::class, 'update'])->name('admin.Fanclub.update');
    Route::post('Fanclub_destroy/{id}', [FanclubController::class, 'destroy'])->name('admin.Fanclub.destroy');
    Route::post('upload/avatar', [FanclubController::class, 'uploadAvatar'])->name('admin.upload.avatar');

    Route::resource('FanclubItem', FanclubItemController::class);
    Route::get('FanclubItem_edit/{id}/edit', [FanclubController::class, 'edit'])->name('admin.FanclubItem.edit');
    Route::post('FanclubItem_update/{id}', [FanclubController::class, 'update'])->name('admin.FanclubItem.update');
    Route::post('FanclubItem_destroy/{id}', [FanclubController::class, 'destroy'])->name('admin.FanclubItem.destroy');


});
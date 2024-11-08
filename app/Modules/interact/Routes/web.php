<?php

use Illuminate\Support\Facades\Route;
use App\Modules\interact\Controllers\UserPageController;

// Define routes here

Route::group( ['prefix'=>'admin/'  , 'as' => 'admin.' ],function(){
    Route::resource('userpage', UserPageController::class);
    Route::get('userpage/{id}/edit', [UserPageController::class, 'edit'])->name('admin.userpage.edit');
    Route::post('userpage_update/{id}', [UserPageController::class, 'update'])->name('admin.userpage.update');
    Route::post('userpage_destroy/{id}', [UserPageController::class, 'destroy'])->name('admin.userpage.destroy');


});
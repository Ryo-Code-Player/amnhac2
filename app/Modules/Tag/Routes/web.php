<?php

use Illuminate\Support\Facades\Route;

use App\Modules\Tag\Controllers\TagController;

// Define routes for Tag module
Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function () {
    Route::resource('tag', TagController::class);
    Route::get('tag/create', [TagController::class, 'create'])->name('tag.create'); // Form tạo mới
    Route::post('tag', [TagController::class, 'store'])->name('tag.store'); // Xử lý thêm mới
    Route::get('tag/{tag}/edit', [TagController::class, 'edit'])->name('tag.edit');
    Route::put('tag/{tag}', [TagController::class, 'update'])->name('tag.update');
    Route::delete('tag/{tag}', [TagController::class, 'destroy'])->name('tag.destroy'); // Xóa
    Route::get('tag_search',[ TagController::class,'search'])->name('tag.search');
});


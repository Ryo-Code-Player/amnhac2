<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Composer\Controllers\ComposerController;

// Define routes here
Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function () {
    Route::resource('composer', ComposerController::class);
    Route::get('composer/create', [ComposerController::class, 'create'])->name('composer.create'); // Form tạo mới
    Route::post('composer', [ComposerController::class, 'store'])->name('composer.store'); // Xử lý thêm mới
    Route::get('composer/{id}/edit', [ComposerController::class, 'edit'])->name('composer.edit');
    Route::put('composer/{id}', [ComposerController::class, 'update'])->name('composer.update');
    Route::delete('composer/{id}', [ComposerController::class, 'destroy'])->name('composer.destroy'); // Xóa
    Route::post('composer/{id}/status', [ComposerController::class, 'status'])->name('composer.status');
    Route::get('composer/{id}', [ComposerController::class, 'show'])->name('composer.show');
    Route::post('upload/avatar', [ComposerController::class, 'uploadAvatar'])->name('upload.avatar');
    Route::get('composer_search',[ ComposerController::class,'search'])->name('composer.search');
});

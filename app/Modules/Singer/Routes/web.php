<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Singer\Controllers\SingerController;

// Define routes here
Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function () {
    Route::resource('singer', SingerController::class);
    Route::get('singer/create', [SingerController::class, 'create'])->name('singer.create'); // Form tạo mới
    Route::post('singer', [SingerController::class, 'store'])->name('singer.store'); // Xử lý thêm mới
    Route::get('singer/{id}/edit', [SingerController::class, 'edit'])->name('singer.edit');
    Route::put('singer/{id}', [SingerController::class, 'update'])->name('singer.update');
    Route::delete('singer/{id}', [SingerController::class, 'destroy'])->name('singer.destroy'); // Xóa
    Route::post('singer/{id}/status', [SingerController::class, 'status'])->name('singer.status');
    Route::get('singer/{id}', [SingerController::class, 'show'])->name('singer.show');
    Route::post('upload/avatar', [SingerController::class, 'uploadAvatar'])->name('upload.avatar');
    Route::get('singer_search',[ SingerController::class,'search'])->name('singer.search');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Song\Controllers\SongController; // Thay đổi controller tương ứng
use App\Modules\Resource\Controllers\ResourceController;


// Define routes here
Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function() {
    // Song Routes
    Route::resource('song', SongController::class);
    
    // Các route cho Song
    Route::get('song/create', [SongController::class, 'create'])->name('song.create'); // Form tạo mới
    Route::post('song', [SongController::class, 'store'])->name('song.store'); // Xử lý thêm mới
    Route::get('song/{id}/edit', [SongController::class, 'edit'])->name('song.edit'); // Form chỉnh sửa
    Route::put('song/{id}', [SongController::class, 'update'])->name('song.update'); // Xử lý cập nhật
    Route::delete('song/{id}', [SongController::class, 'destroy'])->name('song.destroy'); // Xóa
    Route::post('song/{id}/status', [SongController::class, 'status'])->name('song.status'); // Đổi trạng thái
    Route::get('song/{id}', [SongController::class, 'show'])->name('song.show'); // Xem chi tiết bài hát

    // Các route liên quan đến tài nguyên (resource)
    Route::post('upload/avatar', [SongController::class, 'uploadAvatar'])->name('upload.avatar'); // Upload avatar
    Route::post('song/{songId}/upload-resource', [SongController::class, 'uploadResource'])->name('song.uploadResource'); // Upload tài nguyên cho bài hát
    Route::post('song/resource/delete', [SongController::class, 'deleteResource'])->name('song.resource.delete');
    Route::get('song_search',[ SongController::class,'search'])->name('song.search');







});

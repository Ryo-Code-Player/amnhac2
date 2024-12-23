<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Playlist\Controllers\PlaylistController; // Thay đổi controller tương ứng với Playlist

// Define routes for Playlist
Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function() {
    // Playlist Routes
    Route::resource('playlist', PlaylistController::class);
    
    // Các route cho Playlist
    Route::get('playlist/create', [PlaylistController::class, 'create'])->name('playlist.create'); // Form tạo mới
    Route::post('playlist', [PlaylistController::class, 'store'])->name('playlist.store'); // Xử lý thêm mới
    Route::get('playlist/{id}/edit', [PlaylistController::class, 'edit'])->name('playlist.edit'); // Form chỉnh sửa
    Route::put('playlist/{id}', [PlaylistController::class, 'update'])->name('playlist.update'); // Xử lý cập nhật
    Route::delete('playlist/{id}', [PlaylistController::class, 'destroy'])->name('playlist.destroy'); // Xóa
    Route::get('playlist/{id}', [PlaylistController::class, 'show'])->name('playlist.show'); // Xem chi tiết Playlist
    Route::get('playlist_search',[ PlaylistController::class,'search'])->name('playlist.search');
  
});

<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Listener\Controllers\ListenerController;

// Define routes here
Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function () {
    Route::resource('listener', ListenerController::class);
    Route::get('listener/create', [ListenerController::class, 'create'])->name('listener.create'); // Form tạo mới
    Route::post('listener', [ListenerController::class, 'store'])->name('listener.store'); // Xử lý thêm mới
    Route::get('listener/{id}/edit', [ListenerController::class, 'edit'])->name('listener.edit');
    Route::put('listener/{id}', [ListenerController::class, 'update'])->name('listener.update');
    Route::delete('listener/{id}', [ListenerController::class, 'destroy'])->name('listener.destroy'); // Xóa
    Route::post('listener/{id}/status', [ListenerController::class, 'status'])->name('listener.status'); // Thay đổi trạng thái
    Route::get('listener/{id}', [ListenerController::class, 'show'])->name('listener.show');
    Route::get('listener_search',[ ListenerController::class,'search'])->name('listener.search');
   
});

<?php

use Illuminate\Support\Facades\Route;
use App\Modules\MusicType\Controllers\MusicTypeController;

// Define routes here
Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function () {
    Route::resource('musictype', MusicTypeController::class);

    
    Route::get('musictype/create', [MusicTypeController::class, 'create'])->name('musictype.create'); // Form tạo mới
    Route::post('musictype', [MusicTypeController::class, 'store'])->name('musictype.store'); // Xử lý thêm mới
    Route::get('musictype/{id}/edit', [MusicTypeController::class, 'edit'])->name('musictype.edit');
    Route::put('musictype/{id}', [MusicTypeController::class, 'update'])->name('musictype.update');
    Route::delete('musictype/{id}', [MusicTypeController::class, 'destroy'])->name('musictype.destroy'); // Xóa
    Route::post('musictype/{id}/status', [MusicTypeController::class, 'status'])->name('musictype.status');
    Route::get('musictype/{id}', [MusicTypeController::class, 'show'])->name('musictype.show');
    Route::get('musictype_search',[ MusicTypeController::class,'search'])->name('musictype.search');
});

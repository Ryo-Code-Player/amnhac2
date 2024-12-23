<?php

use Illuminate\Support\Facades\Route;
use App\Modules\MusicCompany\Controllers\MusicCompanyController;

// Define routes here
Route::group( ['prefix'=>'admin/'  , 'as' => 'admin.' ],function(){
    Route::resource('musiccompany', MusicCompanyController::class);
    Route::get('musiccompany/create', [MusicCompanyController::class, 'create'])->name('musiccompany.create'); // Form tạo mới
    Route::post('musiccompany', [MusicCompanyController::class, 'store'])->name('musiccompany.store'); // Xử lý thêm mới
    Route::get('musiccompany/{id}/edit', [MusicCompanyController::class, 'edit'])->name('musiccompany.edit');
    Route::put('musiccompany/{id}', [MusicCompanyController::class, 'update'])->name('musiccompany.update');
    Route::delete('musiccompany/{id}', [MusicCompanyController::class, 'destroy'])->name('musiccompany.destroy'); // Xóa
    Route::post('musiccompany/{id}/status', [MusicCompanyController::class, 'status'])->name('musiccompany.status');
    Route::get('musiccompany/{id}', [MusicCompanyController::class, 'show'])->name('musiccompany.show');
    Route::post('upload/avatar', [MusicCompanyController::class, 'uploadAvatar'])->name('upload.avatar');
    Route::post('musiccompany/{companyId}/upload-resource', [MusicCompanyController::class, 'uploadResource'])->name('musiccompany.uploadResource');
    Route::post('musiccompany/resource/delete', [MusicCompanyController::class, 'deleteResource'])->name('musiccompany.resource.delete');
    Route::get('musiccompany_search',[ MusicCompanyController::class,'search'])->name('musiccompany.search');
});

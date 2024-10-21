<?php

use App\Http\Controllers\UserController;
use App\Modules\Comments\Controllers\CommentController;
use App\Modules\Comments\Controllers\ItemController; // Đừng quên import ItemController
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth'], function () {
    // Routes cho Comments
    Route::prefix('comments')->group(function () {
        Route::get('/', [CommentController::class, 'index'])->name('comments.index');
        Route::get('{id}/edit', [CommentController::class, 'edit'])->name('comments.edit');
        Route::put('{id}', [CommentController::class, 'update'])->name('comments.update');
        Route::delete('{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
        Route::put('{id}/status', [CommentController::class, 'updateStatus'])->name('comments.updateStatus');
    });

    // Routes cho Users
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('{id}', [UserController::class, 'show'])->name('users.show');
    });

    // Routes cho Items
    Route::prefix('items')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('admin.items.index');
        Route::get('{id}', [ItemController::class, 'show'])->name('items.show');
    });
    
});

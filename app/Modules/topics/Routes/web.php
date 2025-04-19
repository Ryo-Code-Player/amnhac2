<?php

use Illuminate\Support\Facades\Route;

// Define routes here
use App\Modules\topics\Controllers\topicsController;
use App\Modules\topics\Controllers\TopicMusicController;


// Define routes here
Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function () {
    Route::resource('topic', topicsController::class);


    Route::get('topic/{id}/edit', [topicsController::class, 'edit'])->name('topic.edit');
    Route::put('topic/{id}', [topicsController::class, 'update'])->name('topic.update');
    Route::delete('topic/{id}', [topicsController::class, 'destroy'])->name('topic.destroy'); // Xóa
    Route::post('topic/{id}/status', [topicsController::class, 'status'])->name('topic.status');
    Route::get('topic/{id}', [topicsController::class, 'show'])->name('topic.show');


    Route::resource('topic_music', TopicMusicController::class);


    Route::get('topic_music/{id}/edit', [TopicMusicController::class, 'edit'])->name('topic_music.edit');
    Route::put('topic_music/{id}', [TopicMusicController::class, 'update'])->name('topic_music.update');
    Route::delete('topic_music/{id}', [TopicMusicController::class, 'destroy'])->name('topic_music.destroy'); // Xóa
    Route::post('topic_music/{id}/status', [TopicMusicController::class, 'status'])->name('topic_music.status');
    Route::get('topic_music/{id}', [TopicMusicController::class, 'show'])->name('topic_music.show');
});
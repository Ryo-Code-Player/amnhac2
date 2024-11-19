<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Event\Controllers\EventController;
use App\Modules\Event\Controllers\EventTypeController;
use App\Modules\Event\Controllers\EventUserController;
use App\Modules\Event\Controllers\EventGroupController;

// Define routes here
Route::group( ['prefix'=>'admin/'  , 'as' => 'admin.' ],function(){
    Route::resource('Event',  EventController::class);
    Route::get('Event_edit/{id}/edit', [EventController::class, 'edit'])->name('admin.Event.edit');
    Route::post('Event_update/{id}', [EventController::class, 'update'])->name('admin.Event.update');
    Route::post('Event_destroy/{id}', [EventController::class, 'destroy'])->name('admin.Event.destroy');

    Route::resource('EventType',  EventTypeController::class);
    Route::get('EventType_edit/{id}/edit', [EventTypeController::class, 'edit'])->name('admin.EventType.edit');
    Route::post('EventType_update/{id}', [EventTypeController::class, 'update'])->name('admin.EventType.update');
    Route::post('EventType_destroy/{id}', [EventTypeController::class, 'destroy'])->name('admin.EventType.destroy');

    Route::resource('EventUser',  EventUserController::class);
    Route::get('EventUser_edit/{id}/edit', [EventUserController::class, 'edit'])->name('admin.EventUser.edit');
    Route::post('EventUser_update/{id}', [EventUserController::class, 'update'])->name('admin.EventUser.update');
    Route::post('EventUser_destroy/{id}', [EventUserController::class, 'destroy'])->name('admin.EventUser.destroy');

    Route::resource('EventGroup',  EventGroupController::class);
    Route::get('EventGroup_edit/{id}/edit', [EventGroupController::class, 'edit'])->name('admin.EventGroup.edit');
    Route::post('EventGroup_update/{id}', [EventGroupController::class, 'update'])->name('admin.EventGroup.update');
    Route::post('EventGroup_destroy/{id}', [EventGroupController::class, 'destroy'])->name('admin.EventGroup.destroy');
});
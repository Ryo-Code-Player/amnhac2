<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Group\Controllers\GroupTypeController;
use App\Modules\Group\Controllers\GroupController;

Route::group( ['prefix'=>'admin/'  , 'as' => 'admin.' ],function(){
    Route::resource('group', GroupController::class);
    Route::get('group_search',[GroupController::class,'GroupSearch'])->name('group.search');
    Route::get('group_edit/{id}/edit', [GroupController::class, 'edit'])->name('admin.group.edit');
    Route::post('group_store', [GroupController::class, 'store'])->name('admin.group.store');
    Route::post('group_update/{id}', [GroupController::class, 'update'])->name('admin.group.update');
    Route::post('group_destroy/{id}', [GroupController::class, 'destroy'])->name('admin.group.destroy');
    Route::get('group_show/{id}', [GroupController::class, 'show'])->name('admin.group.show');
    Route::get('group_add/{id}/{link}', [GroupController::class, 'add'])->name('group.add');
    Route::get('group_addgroupuser/{groupid}/{id}/{$link}', [GroupController::class, 'addgroupuser'])->name('group.addgroupuser');
    Route::delete('group_remove/{groupid}/{id}/{$link}', [GroupController::class, 'remove'])->name('group.remove');



    Route::resource('grouptype', GroupTypeController::class);
    Route::get('grouptype_search',[GroupTypeController::class,'GroupTypeSearch'])->name('grouptype.search');
    Route::get('grouptype_edit/{id}/edit', [GroupTypeController::class, 'edit'])->name('admin.grouptype.edit');
    Route::post('grouptype_update/{id}', [GroupTypeController::class, 'update'])->name('admin.grouptype.update');
    Route::post('grouptype_destroy/{id}', [GroupTypeController::class, 'update'])->name('admin.grouptype.destroy');
});
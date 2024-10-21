<?php

use Illuminate\Support\Facades\Route;
use App\Modules\RoleUser\Controllers\RoleUserController;

// Define routes here

Route::group( ['prefix'=>'admin/'  , 'as' => 'admin.' ],function(){
     ///role section
    Route::resource('roleuser', RoleUserController::class);
    Route::get('roleuser/{id}/edit', [RoleUserController::class, 'edit'])->name('admin.roleuser.edit');
    Route::post('roleuser/{id}', [RoleUserController::class, 'update'])->name('admin.roleuser.update');
    Route::delete('admin/roleuser/{roleuser}', [RoleUserController::class, 'destroy'])->name('admin.roleuser.destroy');


 
});
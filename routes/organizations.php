<?php
use Illuminate\Support\Facades\Route;

Route::resources([ 'settings' => 'SettingController','branches' => 'BranchController']);

Route::prefix('branches')->name('branches.')->group(function () {
    Route::get('{branch}/departments', "BranchController@departments")->name('departments.index');
    Route::get('{branch}/departments/create', "BranchController@create_department")->name('departments.create');
    Route::post('{branch}/departments', "BranchController@store_department")->name('departments.store');
    Route::delete('{branch}/departments/{department}', "BranchController@destroy_department")->name('departments.delete');
    Route::get('{branch}/departments/{department}/edit', "BranchController@edit_department")->name('departments.edit');
    Route::patch('{branch}/departments/{department}', "BranchController@update_department")->name('departments.update');
});

Route::prefix('attachments')->group(function () {
    Route::delete('{attachment}', 'AttachmentController@delete');
});


Route::get('roles_permissions', 'ProviderController@roles_permissions');



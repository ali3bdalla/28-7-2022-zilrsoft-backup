<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('configuration')->name('configuration.')->group(function() {
    Route::get('/', 'ConfigurationController@index')->name('index');
    Route::get('/hardware', 'ConfigurationController@hardware')->name('hardware');
});
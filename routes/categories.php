<?php
use Illuminate\Support\Facades\Route;

Route::resources([
        'categories' => 'CategoryController',
        'filters' => 'FilterController',
        'filter_values' => 'FilterValuesController'
 ]);
Route::prefix('categories')->name('categories.')->group(function () {
    Route::post('view/filters', "ProviderController@categories_filters");
    Route::get('{category}/filters', "CategoryController@filters");
    Route::patch('{category}/filters', "CategoryController@update_filters");
    Route::get('{category}/clone', "CategoryController@clone");

});

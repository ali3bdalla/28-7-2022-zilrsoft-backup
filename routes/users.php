<?php
use Illuminate\Support\Facades\Route;

Route::resources([
    'managers' => 'ManagerController',
    'identities' => 'IdentitiesController']);

Route::prefix('gateways')->name('gateways.')->group(function () {
    Route::get('get_gateways_like_to_manager_name', 'ProviderController@get_gateways_like_to_manager_name')
        ->name('load_manager_gateway');
});

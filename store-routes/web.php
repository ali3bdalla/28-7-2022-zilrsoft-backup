<?php

use Illuminate\Support\Facades\Route;
Route::prefix('content')->group(function () {
    Route::get('about', 'ContentController@about');
    Route::get('privacy', 'ContentController@privacy');
    Route::get('terms', 'ContentController@terms');
    Route::get('contact', 'ContentController@contact');
});
Route::get('/orders/{order}/cancel', 'Order\CancelOrderController@showPage');
Route::get('/orders/auth', 'Order\CancelOrderController@auth');
Route::post('/orders/{order}/cancel', 'Order\CancelOrderController@confirm');
Route::get('/orders/{order}/confirm_payment', 'Order\ConfirmOrderPaymentController@showConfirmPaymentPage');
Route::post('/orders/{order}/confirm_payment', 'Order\ConfirmOrderPaymentController@confirmPayment');
Route::group(['middleware' => 'guest:client'], function () {
    Route::group(['as' => 'sign_in.', 'prefix' => 'sign_in'], function () {
        Route::get('/', 'AuthController@signInPage')->name('index');
        Route::post('/', 'AuthController@signIn')->name('store');
    });
    Route::group(['prefix' => 'forget_password', 'as' => 'forget_password.'], function () {
        Route::get('/', 'AuthController@forgetPasswordPage')->name('index');
        Route::post('/', 'AuthController@forgetPassword')->name('store');
        Route::get('/reset', 'AuthController@resetPasswordPage')->name('reset.index');
        Route::post('/reset', 'AuthController@confirmResetPassword')->name('reset.store');
    });
    Route::group(['as' => 'sign_up.', 'prefix' => 'sign_up'], function () {
        Route::get('/', 'AuthController@signUpPage')->name('index');
        Route::post('/', 'AuthController@signUp')->name('store');
        Route::get('verify', 'AuthController@verify')->name('verify');
    });
});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', 'CartController@index')->name('index');
    Route::get('redirect', 'CartController@redirectToLogin')->name('redirect');
});

Route::get('/', 'HomeController@index')->name('index');

Route::prefix('/categories')->name('categories.')->group(
    function () {
        Route::get('/{category}', 'CategoryController@show')->name('show');
    }
);

Route::prefix('items')->name('items.')->group(
    function () {
        Route::get('/search/results', 'ItemController@search')->name('search');
        Route::get('/', 'ItemController@index')->name('index');
        Route::get('/{itemSlug}', 'ItemController@show')->name('show');
    }
);

Route::middleware('auth:client')->group(
    function () {
        Route::get('logout', function () {
            auth('client')->logout();

            return back();
        });
        Route::group(['prefix' => 'profile', 'namespace' => 'Profile', 'name' => 'profile.'], function () {
            Route::get('/', 'IndexController@index');
            Route::get('create-shipping-address', 'ShippingAddressController@create');
            Route::post('create-shipping-address', 'ShippingAddressController@store')->name('create-shipping-address');
            Route::patch('update-information', 'IndexController@updateInformation')->name('update-information');
            Route::post('update-password', 'IndexController@updatePassword')->name('update-password');
            Route::post('update-phone-number', 'IndexController@updatePhoneNumber')->name('update-phone-number');
        });
    }
);


<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'Web\HomeController@toWeb')->name('to.web');
Route::prefix('web')->namespace('Web')->middleware(['ecommerceMiddleware'])->name('web.')->group(
    function () {
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

        Route::middleware('guest:client')->group(
            function () {
                Route::prefix('sign_in')->name('sign_in.')->group(
                    function () {
                        Route::get('/', 'AuthController@signInPage');
                        Route::post('/', 'AuthController@signIn');
                    }
                );
                Route::prefix('forget_password')->name('forget_password.')->group(
                    function () {
                        Route::get('/', 'AuthController@forgetPasswordPage');
                        Route::post('/', 'AuthController@forgetPassword');
                        Route::get('/confirm', 'AuthController@confirmForgetPasswordPage');
                        Route::post('/confirm', 'AuthController@confirmForgetPassword');

                        Route::get('/reset', 'AuthController@resetPasswordPage');
                        Route::post('/reset', 'AuthController@confirmResetPassword');
                    }
                );
                Route::post('/resend_otp', 'AuthController@resendOtp');
                Route::prefix('sign_up')->name('sign_up.')->group(
                    function () {
                        Route::get('/', 'AuthController@signUpPage');
                        Route::post('/', 'AuthController@signUp');
                        Route::post('/confirm_sign_up', 'AuthController@confirmSignUp');
                        Route::get('/confirm_sign_up', 'AuthController@confirmSignUpPage')->name('confirm_sign_up');
                    }
                );
            }
        );

        Route::prefix('cart')->name('cart.')->group(
            function () {
                Route::get('/', 'CartController@index')->name('index');
                Route::get('redirect', 'CartController@redirectToLogin')->name('redirect');
            }
        );

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
    }
);


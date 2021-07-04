<?php

use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\CategoryController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\VerificationCodeController;
use App\Http\Controllers\Site\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| site Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {


    Route::group(['namespace' => 'Site'/*, 'middleware' => 'guest'*/], function () {
        //guest  user
//        Route::get('fat','PaymentController@fatoorah');
        route::get('/', [HomeController::class, 'home'])->name('home')->middleware('VerifiedUser');
        route::get('category/{slug}', [CategoryController::class, 'productsBySlug'])->name('category');
        route::get('product/{slug}', [ProductController::class, 'productsBySlug'])->name('product.details');

        /**
         *  Cart routes
         */
        Route::group(['prefix' => 'cart'], function () {
            Route::get('/', [CartController::class, 'getIndex'])->name('site.cart.index');
            Route::post('/cart/add/{slug?}', [CartController::class, 'postAdd'])->name('site.cart.add');
            Route::post('/update/{slug}', [CartController::class, 'postUpdate'])->name('site.cart.update');
            Route::post('/update-all', [CartController::class, 'postUpdateAll'])->name('site.cart.update-all');
        });
    });


    Route::group(['namespace' => 'Site', 'middleware' => ['auth', 'VerifiedUser']], function () {
        // must be authenticated user and verified
        Route::get('profile', function () {
            return 'You Are Authenticated ';
        });
    });

    Route::group(['namespace' => 'Site', 'middleware' => 'auth'], function () {
        // must be authenticated user
        Route::post('verify-user/', [VerificationCodeController::class, 'verify'])->name('verify-user');
        Route::get('verify', [VerificationCodeController::class, 'getVerifyPage'])->name('get.verification.form');
//        Route::get('products/{productId}/reviews', 'ProductReviewController@index')->name('products.reviews.index');
//        Route::post('products/{productId}/reviews', 'ProductReviewController@store')->name('products.reviews.store');
//        Route::get('payment/{amount}', 'PaymentController@getPayments') -> name('payment');
//        Route::post('payment', 'PaymentController@processPayment') -> name('payment.process');

    });

});

Route::group(['namespace' => 'Site', 'middleware' => 'auth'], function () {
    Route::post('wishlist', [WishlistController::class, 'store'])->name('wishlist.store');

    Route::delete('wishlist', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::get('wishlist/products', [WishlistController::class, 'index'])->name('wishlist.products.index');
});




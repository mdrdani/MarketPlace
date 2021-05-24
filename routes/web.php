<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/', 'HomeController@index')->name('home');

Route::get('/categories', 'CategoryController@index')->name('categories');
Route::get('/categories/{id}', 'CategoryController@detail')->name('categories-detail');

// Detail
Route::get('/details/{id}', 'DetailController@index')->name('detail');
Route::post('/details/{id}', 'DetailController@add')->name('detail-add');

Route::get('/success', 'CartController@success')->name('success');
Route::get('/register/success', 'Auth\RegisterController@success')->name('register-success');

// Controller Process Midtrans
Route::post('/checkout/callback','CheckoutController@callback')->name('midtrans-callback');

Route::group(['middleware' => ['auth']], function(){
                // Cart
            Route::get('/cart', 'CartController@index')->name('cart');
            Route::delete('/cart/{id}', 'CartController@delete')->name('cart-delete');

            // controller  process midtrans
            Route::post('/checkout', 'CheckoutController@process')->name('checkout');

                        // Controller Dashboard
            Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

            // Controller Products
            Route::get('/dashboard/products', 'DashboardProductController@index')->name('dashboard-product');
            Route::get('/dashboard/products/add', 'DashboardProductController@create')->name('dashboard-product-details-create');
            Route::get('/dashboard/products/{id}', 'DashboardProductController@details')->name('dashboard-product-details');
            Route::post('/dashboard/products','DashboardProductController@store')->name('dashboard-product-store');
            Route::post('/dashboard/products/{id}','DashboardProductController@update')->name('dashboard-product-update');
            Route::post('/dashboard/products/gallery/upload','DashboardProductController@uploadgallery')->name('dashboard-product-gallery-upload');
            Route::get('/dashboard/products/gallery/delete/{id}','DashboardProductController@deletegallery')->name('dashboard-product-gallery-delete');
                

            // Controller Transactions
            Route::get('/dashboard/transactions', 'DashboardTransactionController@index')->name('dashboard-transaction');
            Route::get('/dashboard/transactions/{id}', 'DashboardTransactionController@details')->name('dashboard-transaction-details');

            // Controller Setting and Account
            Route::get('/dashboard/settings', 'DashboardSettingController@store')->name('dashboard-settings-store');
            Route::get('/dashboard/account', 'DashboardSettingController@account')->name('dashboard-settings-account');
            Route::post('/dashboard/account/{redirect}', 'DashboardSettingController@update')->name('dashboard-settings-redirect');

});

// 
Route::prefix('admin')
        ->namespace('Admin')
        ->middleware(['auth','admin'])
        ->group(function(){
            Route::get('/', 'DashboardController@index')->name('admin-dashboard');
            Route::resource('category','CategoryController');
            Route::resource('user','UserController');
            Route::resource('product', 'ProductController');
            Route::resource('productgallery','ProductGalleryController');
        });

Auth::routes();
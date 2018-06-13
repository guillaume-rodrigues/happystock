<?php

use Illuminate\Http\Request;

define('ROUTE_PRODUCT','products');
define('ROUTE_PRODUCT_ID','products/{id}');
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Auth routes
Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');
    // Product routes
    Route::post(ROUTE_PRODUCT, 'ProductController@createProduct');
    Route::get(ROUTE_PRODUCT, 'ProductController@getProductsList');
    Route::get(ROUTE_PRODUCT_ID, 'ProductController@getProductDetail');
    Route::post(ROUTE_PRODUCT_ID, 'ProductController@updateProduct');
    Route::delete(ROUTE_PRODUCT_ID, 'ProductController@deleteProduct');
    Route::get('stats', 'StatsController@getStats');
});

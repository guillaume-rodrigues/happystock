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

Route::post('register', 'Auth\RegisterController@register');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Product routes
Route::post(ROUTE_PRODUCT, 'ProductController@createProduct');
Route::get(ROUTE_PRODUCT, 'ProductController@getProductsList');
Route::get(ROUTE_PRODUCT_ID, 'ProductController@getProductDetail');
Route::post(ROUTE_PRODUCT_ID, 'ProductController@updateProduct');
Route::delete(ROUTE_PRODUCT_ID, 'ProductController@deleteProduct');

<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user('api');
});


Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'UserController@details');

});

//resource api
Route::apiResources(['customer'=>'CustomerController']);
Route::apiResources(['category'=>'CategoryController']);
Route::apiResources(['product'=>'ProductController']);
Route::apiResources(['supplier'=>'SupplierController']);
Route::apiResources(['purchase'=>'PurchaseController']);
Route::apiResources(['sales'=>'SalesController']);
Route::apiResources(['user'=>'UserController']);
//search api
Route::get('/search/customer/{field}/{query}','CustomerController@search');
Route::get('/search/product/{field}/{query}','ProductController@search');
Route::get('/search/supplier/{field}/{query}','SupplierController@search');
Route::get('/search/category/{query}','CategoryController@search');
Route::get('/search/user/{field}/{query}','UserController@search');
Route::get('/search/purchase/{field}/{query}','PurchaseController@search');
//all dataget api
Route::get('/allproduct','ProductController@allproduct');
Route::get('/allsupplier','SupplierController@allsupplier');
Route::get('/allcategory','CategoryController@allcategory');
Route::get('/allcustomer','CustomerController@allcustomer');
Route::get('/alluser','UserController@alluser');
Route::get('/allpurchase','PurchaseController@allpurchase');

//sales
Route::get('/salesdata','SalesController@salesdata');
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

Route::get('/', function () {
    return view('welcome');
});

//访问passprot
Route::get('/port','Port\PortController@port'); //访问注册页面
Route::get('/reg','Port\PortController@reg'); //访问注册页面
Route::post('/reg','Port\PortController@doreg'); //用户注册页面
Route::get('/login','Port\PortController@login'); //访问登录页面
Route::post('/login','Port\PortController@dologin'); //登录页面
Route::get('/login1','Port\PortController@dologin'); //登录页面
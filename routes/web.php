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


//PC端
Route::get('/login1','Port\PortController@dologin'); //登录页面
Route::get('/apilogin','Port\PortController@alogin'); //访问登录页面
Route::post('/apilogin','Port\PortController@apilogin'); //登录页面




//APP端
Route::get('/login1','Port\PortController@login'); //访问登录页面  手机端
Route::post('/login1','Port\PortController@dologin'); //登录页面  手机端
Route::post('/appquit','Port\PortController@appquit'); //登录页面  手机端

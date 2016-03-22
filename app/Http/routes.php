<?php

Route::get('/', function () {
    Log::info('Showing user profile for user: ');
    return view('welcome');
});

/***************	Begin 用户认证系统自带控制器处理		**************/
Route::get('login', 'Auth\AuthController@getPhoneLogin');
Route::post('login', 'Auth\AuthController@postPhoneLogin');

Route::get('login/email', 'Auth\AuthController@getEmailLogin');
Route::post('login/email', 'Auth\AuthController@postEmailLogin');
Route::get('logout', 'Auth\AuthController@getLogout');


/***************	Begin 用户注册及密码重置	**************/
Route::get('chose', 'Auth\AuthController@getChoseRegRole');
Route::post('chose', 'Auth\AuthController@postChoseRegRole');
Route::get('register/{role}', 'Auth\AuthController@getPhoneRegister');
Route::post('register', 'Auth\AuthController@postPhoneRegister');
Route::get('reset', 'Auth\PasswordController@getPhoneReset');
Route::post('reset', 'Auth\PasswordController@postPhoneReset');
Route::get('reset/confirm', 'Auth\PasswordController@getPhoneResetConfirm');
Route::post('reset/confirmed', 'Auth\PasswordController@postPhoneResetConfirm');

Route::get('register/email', 'Auth\AuthController@getEmailRegister');
Route::post('register/email', 'Auth\AuthController@postEmailRegister');
Route::get('active/email/{token}','Auth\AuthController@getActiveEmail');
Route::get('reset/email', 'Auth\PasswordController@getEmail');
Route::post('reset/email', 'Auth\PasswordController@postEmail');
Route::get('reset/email/{token}', 'Auth\PasswordController@getEmailReset');
Route::post('reset/email/confirmed', 'Auth\PasswordController@postEmailReset');

Route::group(['prefix' => 'thirds'], function(){
    Route::get('chose','AuthThirdsController@getChoseType');
    Route::post('chose','AuthThirdsController@postChoseType');
    Route::get('bind','AuthThirdsController@getBindExistUser');
    Route::post('bind','AuthThirdsController@postBindExistUser');
    Route::get('new','AuthThirdsController@getBindNewUser');
    Route::post('new','AuthThirdsController@postBindNewUser');
});

Route::group(['prefix' => 'wx'], function() {
    Route::get('login','AuthWeChatController@wxLogin');
    Route::any('callback','AuthWeChatController@wxCallback');
    Route::get('check','AuthWeChatController@wxCheck');
});


Route::group(['prefix' => 'tool'], function(){
    Route::post('cpt_check','ToolsController@captchaCheck');
    Route::get('cpt','ToolsController@getCaptcha');
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

Route::group(['prefix' => 'communicate'], function(){
    Route::get('phone_code','CommunicationController@sendPhoneCode');
    Route::post('message','CommunicationController@sendMessageByRequest');
});

Route::group(['prefix' => 'test'], function(){
    Route::get('put/{key}-{value}','TestController@putValue');
    Route::get('get/{key}','TestController@getValue');
});





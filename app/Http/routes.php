<?php

Route::get('/', function () {
    return view('welcome');
});

/***************	Begin 用户认证系统自带控制器处理		**************/
Route::get('login', 'Auth\AuthController@getPhoneLogin');
Route::post('login', 'Auth\AuthController@postPhoneLogin');

Route::get('login/email', 'Auth\AuthController@getEmailLogin');
Route::post('login/email', 'Auth\AuthController@postEmailLogin');
Route::get('logout', 'Auth\AuthController@getLogout');


/***************	Begin 用户注册及密码重置	**************/
Route::get('register', 'Auth\AuthController@getPhoneRegister');
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


Route::group(['prefix' => 'tool'], function()
{
    Route::post('cpt_check','ToolsController@captchaCheck');
    Route::get('cpt','ToolsController@getCaptcha');
    Route::get('phone_code','ToolsController@sendPhoneCode');
    Route::post('message','ToolsController@sendMessageByRequest');
});

Route::group(['prefix' => 'system'], function()
{
    Route::get('/','SystemController@index');
    Route::get('/phpinfo','SystemController@phpinfo');
    Route::get('/clear','SystemController@dbClear');
    Route::get('/backup','SystemController@dbBackup');
    Route::get('/recovery','SystemController@dbRecovery');
    Route::get('/maker/{model}','SystemController@mcMaker');
    Route::get('/faker/{model}','SystemController@dataFaker');
});

Route::group(['prefix' => 'test'], function()
{
    Route::get('cache/{key}-{value}','TestController@getCache');

});





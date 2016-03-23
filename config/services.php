<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | 第三方登录
    |--------------------------------------------------------------------------
    | 第三方登录账户管理
    |
    */
    'qq' => [
        'client_id'     => '101278262',
        'client_secret' => '2a9b4fa482ea566e5b8a2c80c3e806a4',
        'redirect'      => 'http://exingdong.com/bind/qq/callback',
    ],
    'weibo' => [
        'client_id'     => '4052205488',
        'client_secret' => 'cedfbfd62a3eb07ba9947671f9ffa8f5',
        'redirect'      => 'http://exingdong.com/bind/wb/callback',
    ],
    'wechat' => [
        'client_id'     => 'wx92b14518e2ea48dc',
        'client_secret' => 'd6b32578526ad7e015150abe0f66602a',
        'redirect'      => 'http://www.lawood.cn/wx/callback',
    ],
];

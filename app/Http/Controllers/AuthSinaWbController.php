<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Socialite;
use Illuminate\Support\Facades\Auth;
use App\User;


class AuthThirdController extends Controller
{
    /**
     * 微博登录页面
     *
     * @return mixed
     */
    public function wbLogin()
    {
        echo 'I am going to WeiBo Login page';
        return  Socialite::driver('weibo')->redirect();
    }

    /**
     * 微博登录回调
     *
     */
    public function wbCallback()
    {
        echo "I am WeiBo provider callback handler";
        $user = Socialite::driver('weibo')->user();
        dd($user);
    }
}


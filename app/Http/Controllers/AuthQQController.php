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
     * QQ登录页面
     *
     * @return mixed
     */
    public function qqLogin()
    {
        echo 'I am going to QQ Login Page';
        return  Socialite::driver('qq')->redirect();
    }

    /**
     * QQ登录回调处理
     *
     */
    public function qqCallback()
    {
        echo "I am qq provider callback handler";
        $user = Socialite::driver('qq')->user();
        dd($user);
    }
}


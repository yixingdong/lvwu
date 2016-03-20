<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Socialite;
use Illuminate\Support\Facades\Auth;


class AuthThirdController extends Controller
{
    /**
     * 微信登陆页面
     *
     * @return mixed
     */
    public function wxLogin()
    {
        echo 'I am going to WeiChat Login page';
        return  Socialite::driver('wechat')->redirect();
    }

    /**
     * 微信登陆回调处理
     *
     * @return mixed
     */
    public function wxCallback()
    {
        echo "I am WeChat provider callback handler";
        $wx_user = Socialite::driver('wechat')->user();

        $user = User::where('wx_id',$wx_user['id'])->first();
        if(is_object($user)){
            Auth::login($user);
            switch ($user->type){
                case 'lawyer':
                case 'assist':
                    return redirect('lawyer/center');
                case 'client':
                    return redirect('/');
                default:
                    return redirect('/')->withErrors('你还没有注册具体类型');
                    break;
            }
        }
        $user = User::create([
            'wx_id' => $wx_user['id']
        ]);

        Auth::login($user);
        return redirect('/')->withErrors('登录成功');
    }

    /**
     * 微信服务器检测
     *
     * @param Server $server
     * @return mixed
     */
    public function wxCheck(Server $server)
    {
        $server->on('message', function($message){
            return "欢迎关注 overtrue！";
        });

        return $server->serve(); // 或者 return $server;
    }


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
     * QQ登录回调处理     *
     *
     */
    public function qqCallback()
    {
        echo "I am qq provider callback handler";
        $user = Socialite::driver('qq')->user();
        dd($user);
    }

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

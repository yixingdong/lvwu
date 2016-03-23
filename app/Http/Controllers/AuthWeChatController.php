<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Socialite;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Log;


class AuthWeChatController extends Controller
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
        Log::info('微信用户id = '.$wx_user['id']);

        return $this->UserBindWeChatAccount($wx_user);
    }


    /**
     * 用户账号与微信账号绑定逻辑（）
     *
     * @param $wx_info
     * @return mixed
     */
    public function UserBindWeChatAccount($wx_info)
    {
        //1 判断用户已经登录
        if(Auth::check()){
            $user = Auth::user();
            //1.1 如果用户不是用微信登陆的，那就为其绑定微信号
            if(!$user->wx_id){
                $result = User::where('wx_id',$wx_info['id'])->first();
                if($result){
                    return redirect('/')->withErrors('这个微信账号已被其他用户绑定，您不能绑定此微信账号');
                }
                $user->wx_id = $wx_info['id'];
                $user->save();
                return redirect('/')->withErrors('完成微信账号绑定');
            }
            //1.2 用户之前已经用微信扫码登录
            return redirect('/')->withErrors('您已登录，无需重新扫码');
        }

        //2 如果用户没有登录
        $user = User::where('wx_id',$wx_info['id'])->first();

        // 如果用户是不是已注册用户，需要创建新用户
        if(is_null($user)){
            $user = User::create([
                'wx_id' => $wx_info['id'],
                'role'  => 'undefined',
            ]);
        }

        Auth::login($user);

        switch ($user->role){
            case 'lawyer':
            case 'assist':
                return redirect('/')->withErrors('欢迎'.$user->role.'使用我们的法律平台');
            case 'client':
                return redirect('/')->withErrors('欢迎咨询用户使用我们的服务');
            case 'undefined':
                return redirect('bind/chose');
            default:
                return redirect('/')->withErrors('您的信息已被记录，恶意攻击将被记录在案');
        }
    }
}


<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use \Socialite;


class ThirdLoginController extends Controller
{

    public function wxLogin()
    {
        echo 'I am going to WeiChat Login page';
        return  Socialite::driver('wechat')->redirect();
    }

    public function wxCallback()
    {
        echo "I am WeChat provider callback handler";
        $wx_user = Socialite::driver('wechat')->user();

        $user = User::where('wx_id',$wx_user['id'])->get();
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

    public function wxCheck(Server $server)
    {
        $server->on('message', function($message){
            return "欢迎关注 overtrue！";
        });

        return $server->serve(); // 或者 return $server;
    }

    public function isUserExist($wx_id)
    {
        $user = User::where('wx_id',$wx_id)->first();
        if(is_object($user)){
            Auth::login($user);
            return redirect('/');
        }
        $user = User::create([
            'wx_id'=>$wx_id
        ]);
        Auth::login($user);
        return redirect('/user/profile/create');
        //1 判断账号是否存在
        //true 登录并跳转到指定页
        //false
    }

    public function qqLogin()
    {
        echo 'I am going to QQ Login Page';
        return  Socialite::driver('qq')->redirect();

    }

    public function qqCallback()
    {
        echo "I am qq provider callback handler";
        $user = Socialite::driver('qq')->user();
        dd($user);
    }

    public function wbLogin()
    {
        echo 'I am going to WeiBo Login page';
        return  Socialite::driver('weibo')->redirect();
    }

    public function wbCallback()
    {
        echo "I am WeiBo provider callback handler";
        $user = Socialite::driver('weibo')->user();
        dd($user);
    }
}

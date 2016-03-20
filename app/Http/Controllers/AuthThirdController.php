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
        try{
            $wx_user = Socialite::driver('wechat')->user();
        }catch (Exception $e){
            dd($e->getMessage());
        }

        return $this->UserBindWeChatAccount($wx_user);
    }


    public function UserBindWeChatAccount($wx_info)
    {
        echo 'I am in Bind Function';
        //1判断是否已经登录
        if(Auth::check()){
            echo 'I am already logged in';
            $user = Auth::user();
            //如果用户不是用微信登陆的，那就为其绑定微信号
            if(!$user->wx_id){
                $result = User::where('wx_id',$wx_info['id'])->first();
                if($result){
                    return redirect('/')->withErrors('这个微信账号已被其他用户绑定，您不能绑定此微信账号');
                }
                $user->wx_id = $wx_info['id'];
                $user->save();
                return redirect('/')->withErrors('完成微信账号绑定');
            }
            return redirect('/')->withErrors('您已登录，无需重新扫码');
        }
        //2 没有登录
        $user = User::where('wx_id',$wx_info['id'])->first();
        //2.1 判断是否有此条记录，有则登录之
        if(is_null($user)){
            //2.2 没有此条记录，创建账户
            $user = User::create([
                'wx_id' => $wx_info['id']
            ]);
        }

        Auth::login($user);

        switch ($user->type){
            case 'lawyer':
            case 'assist':
                return redirect('/')->withErrors('欢迎'.$user->type.'使用我们的法律平台');
            case 'client':
                return redirect('/')->withErrors('欢迎咨询用户使用我们的服务');
            default:
                return redirect('/')->withErrors('你还没有注册具体类型');
        }
    }

    public function releaseWeChatAccount()
    {
        if(Auth::check()){
            $user = Auth::user();
            if($user->wx_id){
                $user->wx_id = null;
            }
            return redirect('/')->withErrors('已经解除了原来的微信账号绑定');
        }
    }

    public function getBindUser()
    {
        return view('thirds.bind_user');
    }

    public function postBindUser(Request $request)
    {
        $phone = $request->get('phone');
        $password = $request->get('password');

        $user = User::where('phone',$phone)->first();
        if($user && $user->password == bcrypt($password)){
            $cur_user = Auth::user();
            $wx_id = $cur_user->wx_id;

            $cur_user->delete();

            $user->wx_id = $wx_id;
            $user->save();

            Auth::login($user);
            return redirect('/')->withErrors('您已成功绑定了指定账号');
        }
        return back()->withErrors('账号密码有误，没能完成绑定');
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
     * QQ登录回调处理
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


    public function bindWeiBoAccount()
    {

    }

    public function bindQQAccount()
    {

    }
}

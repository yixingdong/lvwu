<?php
/**
 * Created by PhpStorm.
 * User: tiandongxiao
 * Date: 2016/3/21
 * Time: 20:39
 */

namespace App\Traits\Auth;


trait AuthBindingTrait
{
    private $id = 'wx_id';

    public function UserBindSocialAccount($social_info)
    {
        $user = Auth::user();
        //1.1 如果用户不是用微信登陆的，那就为其绑定微信号
        if(isset($user->$this->id)){
            $result = User::where('wx_id',$social_info['id'])->first();
            if($result){
                return redirect('/')->withErrors('这个微信账号已被其他用户绑定，您不能绑定此微信账号');
            }
            $user->wx_id = $social_info['id'];
            $user->save();
            return redirect('/')->withErrors('完成微信账号绑定');
        }
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
                'type'  => 'undefined'

            ]);
        }

        Auth::login($user);

        switch ($user->type){
            case 'lawyer':
            case 'assist':
                return redirect('/')->withErrors('欢迎'.$user->type.'使用我们的法律平台');
            case 'client':
                return redirect('/')->withErrors('欢迎咨询用户使用我们的服务');
            case 'undefined':
                return redirect('bind/chose');
            default:
                return redirect('/')->withErrors('您的信息已被记录，恶意攻击将被记录在案');
        }
    }

    /**
     * @return mixed
     */
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

    /**
     * @return mixed
     */
    public function getBindExistUser()
    {
        return view('bind.bind_exist_user');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postBindExistUser(Request $request)
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
     * 返回注册类型选择界面
     *
     * @param Request $request
     * @return mixed
     */
    public function getChoseType(Request $request)
    {
        return view('bind.chose_user_type');
    }

    /**
     * 选择注册类型的逻辑
     *
     * @param Request $request
     * @return mixed
     */
    public function postChoseType(Request $request)
    {
        $type = $request->get('role');
        switch($type){
            case 'lawyer':
            case 'client':
                $user = $request->user();
                if(!isset($user->type)){
                    $user->type = $type;
                    $user->save();
                }
                if(!isset($user->phone)){
                    return redirect('bind/new');
                }
            default:
                return redirect('/')->withError('您的信息已被记录，恶意攻击将被记录在案');
        }
    }

    /**
     * 第三方登录后，再完善手机和密码信息
     *
     * @return mixed
     */
    public function getBindNewUser()
    {
        return view('bind.bind_new_user');
    }

    /**
     * 第三方登录后，再完善手机和密码信息
     *
     * @param Requests\BindNewUserRequest $request
     * @return mixed
     */
    public function postBindNewUser(Requests\BindNewUserRequest $request)
    {
        $user = $request->user();
        $user->phone = $request->get('phone');
        $user->password = bcrypt($request->get('password'));
        switch ($user->type){
            case 'lawyer':
                return redirect();
            case 'client':
                $user->active = true;
                return redirect()->withErrors('尊敬的咨询用户，您的账户已激活');
            default:
                return redirect()->withErrors('您的信息已被记录，恶意攻击将被记录在案');
        }

        Auth::login($user);
        return redirect('/')->withErrors('恭喜您已经完成了注册');
    }
}
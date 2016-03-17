<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PhoneResetRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * 返回手机方式密码重置界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPhoneReset()
    {
        return view('auth.phone_reset');
    }

    /**
     * 手机方式修改密码，提交需验证的手机号码
     *
     * @return mixed
     */
    public function postPhoneReset(PhoneResetRequest $request)
    {
        $key = 'reg_'.$request->get('phone');

        if(!Cache::has($key)){
            return redirect('reset')->withErrors('验证码已失效');
        }

        $value = Cache::get($key);

        if($request->get('vcode') != $value){
            return redirect('reset')->withErrors('验证码不正确');
        }

        return redirect('reset/confirm')->with('phone',$userInfo['phone']);
    }

    /**
     * 返回密码重设确认界面（手机修改密码方式）
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPhoneResetConfirm()
    {
        return view('auth.phone_reset_confirm');
    }

    /**
     * 提交新密码并设置（手机号码方式）
     * @return mixed
     */
    public function postPhoneResetConfirm(Request $request)
    {
        $phone = $request->get('phone');

        $userInfo = array(
            'password'   => $request->get('password'),
            'password_confirmation'   => $request->get('password_confirmation')
        );

        $validator = ValidRule::validator($userInfo,'phone_pwd_2');

        if($validator->passes())
        {
            if(!Cache::has($phone)){
                return redirect('reset')->withErrors('验证码已过期');
            }

            $user = User::where('phone', $phone)->first();
            if($user)
            {
                $user->password = bcrypt($userInfo['password']);
                if($user->save()){
                    return redirect('login')->withErrors('密码修改成功，请登录!');
                }else{
                    return redirect('reset')->withErrors('修改失败，请重试');
                }
            }else{
                return redirect('register')->withErrors('此号码尚未注册，请注册后登录');
            }
        }else{
            return redirect('reset')->withErrors($validator);
        }
    }

    /**
     * 获取用户Email信息的表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEmail()
    {
        return View('auth.email_password');
    }

    /**
     * 验证用户Email是否存在，存在则发送更改密码链接到其邮箱
     *
     */
    public function postEmail(Request $request)
    {
        $userInfo = array(
            'email' => $request->get('email'),
        );

        $validator = ValidRule::validator($userInfo,'post_email');

        if($validator->passes())
        {
            $user = User::where('email',$userInfo['email'])->first();
            if($user){
                $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject('密码重置邮件');
                });

                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        return redirect('/')->withErrors('请登录您的邮箱重新设置密码');

                    case Password::INVALID_USER:
                        return redirect()->back()->withErrors('无效的邮件地址');
                }
            }else{
                return redirect('register/email')->withErrors('此邮箱尚未注册，请先注册用户');
            }
        }else{
            return redirect()->back()->withErrors($validator);
        }
    }

    /**
     * 用户在邮箱中点击修改密码，会携带token进入此控制器，用户在这
     * 里指定的视图中输入信息
     */
    public function getEmailReset($token = null)
    {
        if($token)
        {
            $info = DB::table('password_resets')->where('token',$token)->first();
            if(is_object($info)){
                return View('auth.email_reset')->with('token',$token)->with('email',$info->email);
            }else{
                return redirect('reset/email')->withErrors('通行令牌已失效，请从头来过');
            }
        }
    }

    /**
     * 用户新密码提交以后，再此验证并存入数据库
     * @param $request
     * @return null
     */
    public function postEmailReset(Request $request)
    {
        $userInfo = array(
            'email'         => $request->get('email'),
            'password'      => $request->get('password'),
            'password_confirmation' => $request->get('password_confirmation'),
            'token'         => $request->get('token')
        );
        $validator = ValidRule::validator($userInfo,'post_email_reset');

        if($validator->passes())
        {
            $credentials = $request->only(
                'email', 'password', 'password_confirmation', 'token'
            );

            $response = Password::reset($credentials, function ($user, $password) {
                $this->resetPassword($user, $password);
            });

            switch ($response) {
                case Password::PASSWORD_RESET:
                    return redirect('/')->withErrors('修改密码成功，E行动欢迎您超人归来');

                default:
                    return redirect('reset/email')->withErrors('密码修改失败，请从头来过');
            }
        }else{
            return redirect()->back()->withErrors($validator);
        }
    }
}

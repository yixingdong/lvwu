<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailConfirmRequest;
use App\Http\Requests\PhoneConfirmRequest;
use App\Http\Requests\PhoneResetRequest;
use App\Http\Requests\PostEmailRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

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
        $phone = $request->get('phone');
        $user = User::where('phone', $phone)->first();

        if(!is_object($user)){
            return redirect('register')->withErrors('此号码尚未注册，请注册后登录');
        }

        $key = 'reset_'.$phone;

        if(!Cache::has($key)){
            return back()->withErrors('验证码已失效');
        }

        $value = Cache::get($key);

        if($request->get('code') != $value){
            return back()->withErrors('验证码不正确');
        }

        return redirect('reset/confirm')->with('phone',$phone);
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
     *
     * @return mixed
     */
    public function postPhoneResetConfirm(PhoneConfirmRequest $request)
    {
        $phone = $request->get('phone');

        $user = User::where('phone', $phone)->first();

        $user->password = bcrypt($request->get('password'));

        if(!$user->save()){
            return back()->withErrors('密码修改失败，请重试');
        }
        Auth::login($user);

        return redirect('/')->withErrors('密码修改成功，已为您登录网站!');
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
    public function postEmail(PostEmailRequest $request)
    {
        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject('律屋密码重置邮件');
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return redirect('/')->withErrors('请登录您的邮箱重新设置密码');

            case Password::INVALID_USER:
                return redirect()->back()->withErrors('无效的邮件地址');
        }
    }

    /**
     * 用户在邮箱中点击修改密码，会携带token进入此控制器，用户在这
     * 里指定的视图中输入信息
     */
    public function getEmailReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        $info = DB::table('password_resets')->where('token',$token)->first();

        if($info){
            return View('auth.email_reset')->with('token',$token)->with('email',$info->email);
        }

        return redirect('reset/email')->withErrors('通行令牌已失效，请从头来过');
    }

    /**
     * 用户新密码提交以后，再此验证并存入数据库
     * @param $request
     * @return null
     */
    public function postEmailReset(EmailConfirmRequest $request)
    {
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return redirect('/')->withErrors('修改密码成功，律屋欢迎您超人归来');

            default:
                return redirect('reset/email')->withErrors('密码修改失败，请重试一次');
        }
    }
}

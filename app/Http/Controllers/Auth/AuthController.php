<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\EmailLoginRequest;
use App\Http\Requests\EmailRegRequest;
use App\Http\Requests\PhoneLoginRequest;
use App\Http\Requests\PhoneRegRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'      =>  isset($data['name'])?$data['name']:null,
            'phone'     =>  isset($data['phone'])?$data['phone']:null,
            'email'     =>  isset($data['email'])?$data['email']:null,
            'type'      =>  isset($data['type'])?$data['type']:null,
            'active'    =>  $data['active'],
            'password'  =>  bcrypt($data['password'])
        ]);
    }


    /**
     * 手机注册页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPhoneRegister()
    {
        return view('auth.phone_register');
    }

    /**
     * 手机注册逻辑处理
     *
     * @param PhoneRegRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postPhoneRegister(PhoneRegRequest $request)
    {
        $key = 'reg_'.$request->get('phone');

        if(!Cache::has($key)){
            back()->withErrors('抱歉，验证码已过期');
        }

        if($request->get('vcode') != Cache::get($key)){
            back()->withErrors('验证码不正确');
        }

        $info = array_merge($request->all(),['active'=>true]);
        $user = $this->create($info);

        if(is_object($user)){
            Auth::login($user);
            return redirect('/');
        }
    }

    /**
     * 手机登录页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPhoneLogin()
    {
        return view('auth.phone_login');
    }


    /**
     * 手机登录逻辑处理
     *
     * @param PhoneLoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postPhoneLogin(PhoneLoginRequest $request)
    {
        $phone = $request->get('phone');
        $pwd = $request->get('password');

        if(!Auth::attempt(['phone'=>$phone,'password'=>$pwd])){
            back()->withErrors('账号密码有误');
        }
        return redirect()->intended('/');
    }


    /**
     * 邮件登录页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEmailLogin()
    {
        return view('auth.email_login');
    }

    /**
     * 邮件登录逻辑处理
     *
     * @param EmailLoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postEmailLogin(EmailLoginRequest $request)
    {
        $email = $request->get('email');
        $pwd = $request->get('password');

        if(!Auth::attempt(['email'=>$email,'password'=>$pwd])){
            return redirect('login/email')->withErrors('账号密码有误');
        }
        return redirect()->intended('/');
    }


    /**
     * 邮件注册页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEmailRegister()
    {
        return view('auth.email_regist');
    }

    /**
     * 邮件注册逻辑处理
     *
     * @param EmailRegRequest $request
     * @return $this
     */
    public function postEmailRegister(EmailRegRequest $request)
    {

        $info = array_merge($request->all(),['active'=>false]);
        $user = $this->create($info);

        if(is_object($user)){
            $this->sendActivatedMail($user);
            return redirect('/')->withErrors('恭喜您注册成功!请到您邮箱进行激活');
        }

        back()->withErrors('注册失败，请再试一次');

    }
}

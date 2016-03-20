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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

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
            'active'    =>  isset($data['active'])?$data['active']:false,
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
        return view('auth.phone_reg');
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
        $value = Cache::get($key);
        dd($value);
        if($request->get('code') != Cache::get($key)){
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

        if(Auth::attempt(['phone'=>$phone,'password'=>$pwd])){
            return redirect('/');
        }
        back()->withErrors('账号密码有误');
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

        if(Auth::attempt(['email'=>$email,'password'=>$pwd])){
            return redirect()->to('/');
        }
        return redirect('login/email')->withErrors('账号密码有误');
    }


    /**
     * 邮件注册页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEmailRegister()
    {
        return view('auth.email_reg');
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

    public function getActiveEmail($token = null)
    {
        if($token){
            $info = DB::table('email_actives')->where('token',$token)->first();
            if(is_object($info)){
                $user = User::where('email',$info->email)->first();
                $user->active = true;
                if($user->save()){
                    DB::table('email_actives')->where('token',$token)->delete(); // 删除此条存储记录
                    Auth::login($user);
                    return redirect('/')->withErrors('邮箱已激活并为您登录');
                }
            }
            //已过激活失效期，是否重新发射激活邮件
            return redirect('/')->withErrors('验证信息已过期');
        }
    }

    private function sendActivatedMail($user)
    {

        $data = array(
            array(
                'email'   => $user->email,
                'token'   => Str::random(40),
            )
        );

        $result = DB::table('email_actives')->insert($data);
        if($result){
            $info = $data[0];
            Mail::send('auth.email_active', ['token' => $info['token'] ], function ($m) use ($user) {
                $m->to($user->email)->subject('律屋邮箱绑定');
            });
        }
    }

    public function selectRegType()
    {
        return view('auth.select_type');
    }   

}

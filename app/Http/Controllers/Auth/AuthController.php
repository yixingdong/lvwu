<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\ChoseUserRoleRequest;
use App\Http\Requests\EmailBindRequest;
use App\Http\Requests\EmailLoginRequest;
use App\Http\Requests\EmailRegRequest;
use App\Http\Requests\PhoneLoginRequest;
use App\Http\Requests\PhoneRegRequest;
use App\User;
use GuzzleHttp\Psr7\Request;
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
            'role'      =>  isset($data['role'])?$data['role']:null,
            'active'    =>  isset($data['active'])?$data['active']:false,
            'password'  =>  bcrypt($data['password'])
        ]);
    }

    /**
     * 返回用户注册类型选择页面
     *
     * @return mixed
     */
    public function getChoseRegRole()
    {
        return view('auth.chose_role');
    }

    /**
     * 用户选择注册类型处理逻辑
     *
     * @param ChoseUserRoleRequest $request
     * @return mixed
     */
    public function postChoseRegRole(ChoseUserRoleRequest $request)
    {
        $role = $request->get('role');
        switch($role){
            case 'lawyer':
            case 'client':
                return redirect('register/'.$role);
            default:
                return redirect('/')->withError('您的信息已被记录，恶意攻击将被记录在案');
        }
    }

    /**
     * 返回手机注册页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPhoneRegister($role)
    {
        return view('auth.phone_reg')->with('role',$role);
    }

    /**
     * 手机注册逻辑处理
     *
     * @param PhoneRegRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postPhoneRegister(PhoneRegRequest $request)
    {
        $role = $request->get('role');

        if(!in_array($role,['lawyer','client'])){
            return redirect('/')->withErrors('抱歉,我们没有这样的角色类型供用户注册');
        }

        $key = 'reg_'.$request->get('phone');

        if(!Cache::has($key)){
            return back()->withErrors('抱歉，验证码已过期');
        }

        $value = Cache::get($key);
        Cache::forget($key);

        if($request->get('code') != $value){
            return back()->withErrors('验证码不正确');
        }

        $user = $this->create($request->all());

        if($user){
            Auth::login($user);
            switch ($user->role){
                case 'lawyer':
                    return redirect('/')->withErrors('律师用户注册完成，您需要提交您的执业资质进行审核');
                case 'client':
                    $user->active = true;
                    $user->save();
                    return redirect('/')->withErrors('咨询用户注册完成！您可以搜索您需要的律师了');
                default:
                    return redirect('/')->withErrors('我去，外星人啊');
            }

            return redirect('/')->withErrors('恭喜您已经完成了注册');
        }
    }

    /**
     * 返回手机登录页面
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
        return back()->withErrors('账号密码有误');
    }


    /**
     * 返回邮件登录页面
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
     * 返回邮件注册页面
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
        return back()->withErrors('注册失败，请再试一次');
    }


    /**
     * 发送邮箱激活邮件
     *
     * @param $user
     */
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
            Mail::send('emails.active', ['token' => $info['token'] ], function ($m) use ($user) {
                $m->to($user->email)->subject('律屋邮箱绑定');
            });
        }
    }

    public function getActiveEmail($token = null)
    {
        if($token){
            $info = DB::table('email_actives')->where('token',$token)->first();
            if(is_object($info)){
                return redirect('/')->withErrors('邮件激活已过激活失效期');
            }
            $user = User::where('email',$info->email)->first();
            $user->email_active = true;
            $user->save();
            DB::table('email_actives')->where('token',$token)->delete(); // 删除此条存储记录
            Auth::login($user);
            return redirect('/')->withErrors('邮箱已激活并为您登录');
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class BindController extends Controller
{
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
    public function getChoseRole()
    {
        return view('bind.chose_user_role');
    }

    /**
     * 选择注册类型的逻辑
     *
     * @param Request $request
     * @return mixed
     */
    public function postChoseRole(Requests\ChoseUserRoleRequest $request)
    {
        $role = $request->get('role');
        switch($role){
            case 'lawyer':
            case 'client':
                $user = $request->user();
                $user->role = $role;
                $user->save();
                return redirect('bind/new');
            default:
                return redirect('/')->withError('抱歉,此为无效用户类型');
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
        switch ($user->role){
            case 'lawyer':
                $user->save();
                return redirect('/')->withErrors('恭喜你已经完成律师基本信息，接下来您需要提交您的资质材料进行进一步审核');
            case 'client':
                $user->active = true;
                $user->save();
                return redirect('/')->withErrors('尊敬的咨询用户，您的账户已激活');
            default:
                return redirect('/')->withErrors('您的信息已被记录，恶意攻击将被记录在案');
        }

        Auth::login($user);
        return redirect('/')->withErrors('恭喜您已经完成了注册');
    }

    public function getBindEmail()
    {
        return view('auth.email_bind');
    }

    public function postBindEmail(Request $request)
    {
        $user = $request->user();
        $user->email = $request->get('email');
        $user->save();
        $this->sendActivatedMail($user);
        return redirect('/')->withErrors('恭喜您注册成功!请到您邮箱进行激活');
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

    public function getActiveEmail($token = null)
    {
        if($token){
            $info = DB::table('email_actives')->where('token',$token)->first();
            if(is_object($info)){
                $user = User::where('email',$info->email)->first();
                $user->email_active = true;
                if($user->save()){
                    DB::table('email_actives')->where('token',$token)->delete(); // 删除此条存储记录
                    if(Auth::check()){
                        return redirect('/')->withErrors('邮箱已完成绑定');
                    }
                    Auth::login($user);
                    return redirect('/')->withErrors('邮箱已激活并为您登录');
                }
            }
            //已过激活失效期，是否重新发射激活邮件
            return redirect('/')->withErrors('验证信息已过期');
        }
    }
}


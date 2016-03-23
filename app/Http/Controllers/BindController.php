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

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 返回绑定已有账户界面
     *
     * @return mixed
     */
    public function getBindExistUser()
    {
        return view('bind.bind_exist_user');
    }

    /**
     * 绑定已有账户处理逻辑
     *
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
     * 选择注册类型的处理逻辑
     *
     * @param Request $request
     * @return mixed
     */
    public function postChoseRole(Requests\ChoseUserRoleRequest $request)
    {
        $role = $request->get('role');
        switch($role){
            case 'lawyer':
                $user = $request->user();
                $user->role = $role;
                $user->save();
                return redirect('bind/select');
            case 'client':
                $user = $request->user();
                $user->role = $role;
                $user->active = true;
                $user->save();
                return redirect('/')->withErrors('恭喜，您咨询账号创建完成');
            default:
                return redirect('/')->withError('抱歉,此为无效用户类型');
        }
    }

    /**
     * 返回绑定用户方式选择界面
     * 1 绑定已有账户
     * 2 绑定新账号
     * @return mixed
     */
    public function getBindUser()
    {
        return view('bind.chose_bind_user');
    }

    /**
     * 绑定用户方式选择处理逻辑
     *
     * @param Request $request
     * @return mixed
     */
    public function postBindUser(Request $request)
    {
        $bind = $request->get('bind');
        switch($bind){
            case 'new':
                return redirect('bind/new');
            case 'exist':
                return redirect('bind/exist');
            default:
                return redirect('/')->withError('抱歉,此为无效绑定类型');
        }
    }

    /**
     * 返回绑定新的账号界面
     *
     * @return mixed
     */
    public function getBindNewUser()
    {
        return view('bind.bind_new_user');
    }

    /**
     * 绑定新账号的处理逻辑
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

    /**
     * 返回绑定邮箱地址界面
     *
     * @return mixed
     */
    public function getBindEmail()
    {
        return view('auth.email_bind');
    }

    /**
     * 绑定邮箱地址的处理逻辑
     *
     * @param Request $request
     * @return mixed
     */
    public function postBindEmail(Request $request)
    {
        $user = $request->user();
        $user->email = $request->get('email');
        $user->save();
        $this->sendBindEmailMail($user);
        return redirect('/')->withErrors('绑定邮件已发送到您邮箱!请登录您邮箱的进行绑定');
    }

    /**
     * 发送邮箱绑定邮件给用户
     *
     * @param $user
     */
    private function sendBindEmailMail($user)
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
            Mail::send('emails.bind', ['token' => $info['token'] ], function ($m) use ($user) {
                $m->to($user->email)->subject('律屋邮箱绑定');
            });
        }
    }

    public function getBindEmailHandler($token = null)
    {
        if($token){
            $info = DB::table('email_actives')->where('token',$token)->first();

            if(is_null($info)){ //已过绑定失效期，是否重新发送绑定邮件
                return redirect('/')->withErrors('验证信息已过期');
            }

            $user = User::where('email',$info->email)->first();
            $user->email_active = true;
            $user->save();
            DB::table('email_actives')->where('token',$token)->delete(); // 删除此条存储记录
            return redirect('/')->withErrors('邮箱已绑定并为您登录');
        }
    }
}


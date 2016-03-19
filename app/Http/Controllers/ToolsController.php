<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\Cache;

class ToolsController extends Controller
{
    /**
     * 创建图形验证码图片并返回
     *
     * @param  null
     * @return null
     */
    public function getCaptcha(Request $request)
    {
        return Captcha::create('default');
    }

    /**
     * 进行图形验证码的验证
     *
     * @param  null
     * @return null
     */
    public function captchaCheck(Request $request)
    {
        $cpt = $request->get('cpt');
        if(Captcha::check($cpt)){
            return response()->json(['code' => 200, 'info' => '图片验证码通过']);
        }else{
            return response()->json(['code' => 400, 'info' => '图片验证码错误']);
        }
    }

}


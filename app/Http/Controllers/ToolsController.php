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
     * 发送手机验证码，支持短信，语音两种
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendPhoneCode(Request $request)
    {
        $info = array(
            'method'   =>  'sms',
            'phone'    =>  $request->get('phone'),
            'content'  =>  array($this->randString(4)),
            'template' =>  '74240'
        );

        $result = $this->sendMessage($info);

        if($result){
            Cache::put('reg_'.$info['phone'], $info['content'], 1);
            return response()->json(['code' => 200, 'info' => '验证发送成功']);
        }

        return response()->json(['code' => 400, 'info' => '验证发送失败']);
    }

    /**
     * 发送模板短信
     *
     * @param $phone
     * @param $content
     * @return bool
     */
    public function sendSMS($phone, $content, $template)
    {
        $result = \PhpSms::make()->to($phone)->template('YunTongXun', $template)->data($content)->send();

        if($result['success']){
            return true;
        }
        return false;
    }

    /**
     * 发送语音消息
     *
     * @param $phone
     * @param $content
     * @return bool
     */
    public function sendVoice($phone, $content)
    {
        if(is_array($content)){
            $content = $content[0];
        }
        $result = \PhpSms::voice($content)->to($phone)->send();

        if($result['success']){
            return true;
        }
        return false;
    }

    /**
     * 发送通知短信
     *
     * @param $phone
     * @param $content
     * @return bool
     */
    public function sendNotify($phone, $content)
    {
        //只希望使用内容方式发送,如云片,luosimao
        $result = \PhpSms::make()->to($phone)->content($content)->send();

        if($result['success']){
            return true;
        }

        return false;
    }

    /**
     * 发送消息总接口
     *
     * @param $info
     * @return bool
     */
    public function sendMessage($info)
    {
        \PhpSms::queue(false);

        switch($info['method']){
            case 'sms':
                return $this->sendSMS($info['phone'],$info['content'], $info['template']);

            case 'voice':
                return $this->sendVoice($info['phone'],$info['content']);

            case 'notify':
                return $this->sendNotify($info['phone'],$info['content']);

            default:
                break;
        }
    }

    public function sendMessageByRequest(Request $request)
    {
        $method = $request->get('method');
        $phone = $request->get('phone');
        $content = $request->get('content');

        switch($method){
            case 'sms':
                return $this->sendSMS($phone,$content);

            case 'voice':
                return $this->sendVoice($phone,$content);

            case 'notify':
                return $this->sendNotify($phone,$content);

            default:
                break;
        }
    }

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

    /**
     * 随机生成指定长度的字符串
     *
     * @param  $len 指定生成字符串的长度
     * @param  $format 指定生成字符串的格式
     * @return String
     */
    private function randString($len = 6, $format = 'NUMBER')
    {
        $str = "";
        switch ($format) {
            case 'ALL':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~';
                break;
            case 'CHAR':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-@#~';
                break;
            case 'NUMBER':
                $chars = '0123456789';
                break;
            default :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~';
                break;
        }
        mt_srand((double)microtime() * 1000000 * getmypid());
        while (strlen($str) < $len) {
            $str .= substr($chars, (mt_rand() % strlen($chars)), 1);
        }
        return $str;
    }
}


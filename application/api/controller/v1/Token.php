<?php
/**
 * token处理
 * @name    wuchuheng
 * @email   wuchuehng@163.com
 * @data    2019/06/01
 * @blog    www.wuchuheng.com
 */
namespace app\api\controller\v1;

use think\Controller;
use think\facade\Request;
use think\captcha\Captcha;

class Token extends  Controller
{

    /**
     *  获取token
     *
     */
    public function getToken()
    {    
       //$isBoot = captcha_check(Request::param('vercode', 'post'));
        return array(
          'errorCode' => 0,
          'msg' => 'success',
          'data'=>['access_token' => 'hello,you are login']
        );
    }


    /**
     * 获取验证码码
     *
     */
    public function getVerCode()
    {
			$captcha = new Captcha();
			return $captcha->entry();  
    }


    /**
    *  登录
    * 
    */
    public function logout()
    {
        return array(
          'errorCode' => 0,
          'msg' => 'success',
          'data'=>['access_token' => 'hello,you are login']
        );
    }
}

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
use app\api\validate\Token as TokenValidate;
use app\api\service\Token as TokenService;
use app\lib\exception\ErrorException;

class Token extends  Controller
{

    /**
     *  获取token
     *
     */
    public function getToken()
    {    
        (new TokenValidate())->scene('getToken')->gocheck();
        $token = (new TokenService())->getToken();
       //$isBoot = captcha_check(Request::param('vercode', 'post'));
        return array(
          'errorCode' => 0,
          'msg' => 'success',
          'data'=>['access-token' =>$token]
        );
    }


    /**
     * 获取验证码码
     *
     */
    public function getVerCode()
    {
			$captcha = new Captcha();
      $captcha = new Captcha();
      $captcha->codeSet = '0';
      $captcha->fontSize = 40;
      $captcha->length  = 3;
			return $captcha->entry();  
    }


    /**
    *  登出
    * @http PUT
    * @url  /api/v1/logout
    * @return json
    */
    public function logout()
    {
        (new TokenValidate())->scene('logout')->gocheck();
        if(!(new TokenService())->logout()) {
            throw new  ErrorException();
        } else {
          return array(
            'errorCode' => 0,
            'msg' => 'success',
            'data'=>[]
          );
        }
        
    }
}


<?php 
/**
 * Token停牌管理场景验证器
 * @author wuchuheng
 * @email  wuchuheng@163.com
 * @date   2019-06-19
 *
 */
namespace app\api\validate;

use think\facade\Request;
use app\api\model\Member;
use app\lib\exception\ErrorException;

class Token  extends Base
{
    protected $rule = [
      'account' => 'require',
      'passwd'   => 'require',
      'vercode'  => 'require|checkCode|checkUserInfo'
    ];

    protected $message = [
      'account.require' => '请输入账号',
      'passwd.require'  => '请输入密码',
      'vercode.require' => '请输入图片验证码',
      'vercode.checkCode' => '验证码错误',
      'vercode.checkUserInfo' => '账号或密码错误'
    ];

    //场景定义
    protected $scene = [
        'getToken'   => ['account', 'passwd', 'vercode']
    ];


    /**
    * 验证码验证
    */
    public function checkCode($value)
    {
      if (captcha_check($value)){
          return true;
      } else {
          return false;
      }
    }


    /**
    * 账号密码验证
    *
    */
    public function checkUserInfo($value) {
        $passwd = get_hash(Request::param('passwd', 'get'));
        $account = Request::param('account', 'get');
        $hasData = (new Member())->where('account', $account)
          ->where('passwd', $passwd)
          ->field('uid')
          ->find();
        if ($hasData) {
          return true;
        } else {
          return false;
        }
    }
}



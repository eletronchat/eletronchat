<?php
namespace app\api\service;

use app\api\model\Member;
use think\facade\Request;
use app\api\service\Redis;
use app\lib\exception\ErrorException;

class Token extends Base
{
    /**
    * 获取token
    * @return boolean
    */
    public function getToken()
    {
        $account = Request::param('account/s');
        $passwd  = get_hash(Request::param('passwd/s'));
        $member = (new Member())->where('account', $account)
          ->where('passwd', $passwd)
          ->find();
        //:xxx  这是有数据 更新， 加个事务好些
        $member->web_last_ip = $member->web_current_ip;
        $member->web_current_ip = $_SERVER['REMOTE_ADDR'];
        $member->count = $member->count + 1;
        $member->web_token = md5 ( uniqid ( rand (), true ) );
        $member->save();
        if(!(new Redis())->cacheMember($member)) {
            throw new ErrorException(['msg'=>'缓存出错请联系管理员!']);
        } else {
            return $member->web_token;
        }
        
    }
}


<?php 
namespace app\api\service;

use app\api\model\AuthGroup;
use app\api\model\AuthRule;

class Redis extends Base
{
    protected $redis;

    public function __construct()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $this->redis = $redis;
        //检测权限缓存
        $authGroup =  AuthGroup::field('id,rules')->all();
        foreach($authGroup as $v) {
            $key = "auth_group_" . $v['id'];
            $has_cache = $redis->keys($key);
            if(!$has_cache) {
              $rules = AuthRule::whereIn('id', $v['rules'])
                ->where('name', '<>', '')
                ->field('name')
                ->all();
              $rules = $rules->toArray();
              $values =array_column($rules, 'name');
             $redis->hMSet($key, $values);
            }
        }
    }

    
    /**
     * 缓存用户信息
     * @param   $member   obj   用户数据集
     * @return  boolean
     */
    public function cacheMember(object $member)
    {
        $token = $member->web_token;
        $member = $member->toArray();
        $is_ok = $this->redis->hMSet($token, 'member_' . $member);
        dump($is_ok);
    }
}

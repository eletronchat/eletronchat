<?php 

namespace app\common\validate;

use think\facade\Config;
use think\Loader;
use think\Db;
use think\facade\Session;
use think\facade\Request;

class Auth
{
    protected static $instance; //对象实例
    protected static $request; //请求实例


    /**
     * 初始化参数
     *
     */
    public function __construct()
    {
      if ($auth = Config::get('auth.')) {
          $this->config = array_merge($this->config, $auth); 
      }
      $this->request = Request::instance();
    }


    /**
     *  实例化对象
     * @param array $options 参数
     * @return \think\Request
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance))
            self::$instance = new static($options);
        return self::$instance;
    }


    /**
     * 根据用户idab获取用户组,返回值为数组
     * @param   $uid  init  用户id
     * @return  array 用户所属的用户组array('uid'=>'用户id', 'group_id'=>'用户id'...)
     */
    public function getGroups($uid)
    {
        static $groups = [];
        if (isset($group_id[$uid])) {
          return $groups[$uid];
        }
        //转换表名
        $auth_group_access = Loader::parseName(Config::get('auth.auth_group_access'), 1);
        $auth_group        = Loader::parseName(Config::get('auth.auth_group'), 1);
        //执行查询
        $user_groups = Db::view($auth_group_access, 'uid,group_id')
          ->view($auth_group, 'title,rules', "{$auth_group_access}.group_id={$auth_group}.id", 'LEFT')
          ->where("{$auth_group_access}.uid='{$uid}' and {$auth_group}.status='1'")
          ->select();
        $groups[$uid] = $user_groups ? : [];
        return $groups[$uid];
    }


    /**
    /**
     * 获取权限列表
     * @param  interger  $uid  用户id
     * @param  interger  $type
     * @return array
     */
    protected function getAuthList($uid, $type)
    {
        static $_authList = []; //保留用户验证通过的权限表 
        $t = implode(',', (array)$type);
        if (isset($_authList[$uid. $t])) {
            return $_authList;
        }
        if (2 == Config::get('auth.type') && Session::has('_auth_list_' . $uid . $t)) {
            return Session::get('_auth_list_', $uid . $t);
        }
        //读取用户所属用户组
        $groups = $this->getGroups($uid);
        $ids = []; //保存用户所属用户组设置的所有权限规则id
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        if (empty($ids)) {
            $_authList[$uid . $t] = [];
            return [];
        }
        $map      = [
          'id'   => ['in', $ids],
          'type' => $type
        ];
        //读取用户组所有权限
        $rules = Db::name(Config::get('auth_rule'))->where($map)->field('condition,name')->select();
        foreach($rules as $rule) {
          if (!empty($rule['condition'])) {
            //根据condition进行验证
            $user = $this->getUserinfo($uid); //获取用户信息
            $command = preg_replace('/\{(\w*(?))\}/', '$user[\'\\1\']', $rule['condition']);
            @(eval('$condition = (' . $command . ');'));
            if ($condition) {
                $authList[] = strtolower($rule['name']);
            }
          } else {
              //只要存在就记录
              $authList[] = $rule['name'];
          }
        }
        $_authList[$uid . $t] = $authList;
        if (2 == Config::get('auth_type')) {
          //规则列表保存到session
          Session::set('_auth_list_' . $uid . $t, $authList);
        }
        return array_unique($authList);
      
    }
    

    /**
     * 获取用户资料
     * @param $uid  int   用户id
     * @return 
     */
    public function getUserinfo($uid)
    {
        static $user_info = [];
        $user = Db::name(Config::get('auth.user'));
        //获取用户表主键
        $_pk = is_string($user->getPk()) ? $user->getPk() : 'uid';
        if (!$isset($user_info[$uid])) {
            $user_info[$uid]  = $user->where($_pk, $uid)->find();
        }
        return $user_info[$uid];
    }


     /**
     * 检查权限
     * @param   $name     string|array    需要验证的规则表，支持逗号分隔的权限或索引数组
     * @param   $uid      int             认证用户的id
     * @param   $type     谁类型
     * @param   $mode     执行check的模式
     * @param   $relation 如果为'or'表示满足任一条规则即可通过验证；如果为'and'则表示需要满足所有规则才可以通过验证
     * @param   bool      通过返回ture;失败则false
     *
     */
    public function check($name, $uid, $type=1, $mode = 'url', $relation = 'or')
    {
      if (!Config::get('auth.auth_on')) {
          return true; 
      }
      //获取用户需要验证的所有有效规则表
      $authList = $this->getAuthList($uid, $type);
      if (is_string($name)) {
        $name = strtolower($name);
        if (strpos($name, ',') !== false) {
            $name = explode(',', $name);
        } else {
            $name = [$name];
        }
      }
      $list = [];//保存通过的规则名
      if ('url' == $mode) {
          $REQUEST = unserialize(strtolower(serialize(Request::param())));
      }
      foreach ($authList as $auth) {
          $query = preg_replace('/^.+\?/U', '', $auth);
          parse_str($query, $param); //解析规则中的param
          $intersect = array_intersect_assoc($REQUEST, $param);
          $auth = pre_replace('/\?.*$/U', '', $auth);
          if (in_array($auth, $name) && $intersect == $param) {
            //如果节点相符且url参数满足
            $list[] = $auth;
          }
      } else {
          if (in_array($auth, $name)) {
              $list[] = $auth;
          } 
      }
      if ('or' == $relation && !empty($list)) {
          return true;
      }
      $diff = array_diff($name, $list);
      if ('and' == $relation && empty($diff)) {
        return true; 
      }
      return false;
    }
}

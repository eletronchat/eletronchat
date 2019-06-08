<?php 
namespace app\api\model;

use think\Db;

class Member extends Base
{

    /**
     * 获取客服目录树的未分组数据
     * @return obj  collection
     */
    public function countNotBelong()
    {
      $count = self::where('uid', 'not in', function($query) {
        $query->name('member_group_access')->field('uid');
      }) ->count();
      return $count;
    }


    /**
     * 关联权限分组模型
     *
     */
     public function authGroupAccess()
     {
         return $this->hasOne('AuthGroupAccess', 'group_id', 'uid');
     }


     /**
     * 关联成员成员分组模型
     *
     */
     public function memberGroupAccess()
     {
         return $this->hasOne('MemberGroupAccess', 'member_group_id', 'uid');
     }

}


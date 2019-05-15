<?php 
namespace app\api\model;

use think\Db;

class Member extends Base
{
    /**
     * 客服管理目录树节点名称
     *
     */
    public function getTitleAttr($value, $data)
    {
    
    }


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
}


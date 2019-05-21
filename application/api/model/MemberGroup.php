<?php 
namespace app\api\model;

use app\api\model\Member;

class MemberGroup extends Base
{
  /**
   * 获取客服组目录树
   * @return    obj  
   */
  public function getGroup()
  {
    $result = self::withCount(['memberGroupAccess'=>'count'])
      ->field(['name'=>'title', 'pid', 'id', 'concat(path,"-",id)'=>'fullpath'])
      ->order('fullpath') 
      ->select();
      return $result;
  }
  
  /**
   * 关联户分组-用户中间表
   *
   */ 
  public function memberGroupAccess()
  {
      return $this->hasMany('MemberGroupAccess', 'member_group_id', 'id');
  }
  
}


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
    $result = self::field(['name'=>'title', 'pid', 'id', 'concat(path,"-",pid)'=>'fullpath'])
        ->order('fullpath') 
        ->select();
      return $result;
  }
  
    
}


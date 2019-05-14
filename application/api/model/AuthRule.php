<?php  
namespace app\api\model;

class AuthRule extends Base
{
    /**
     * 获取右则菜单列表
     *
     */
    public function getSideMenu()
    {
      $result = self::where('pid = 0')
        ->where('is_side_menu = 1')
        ->field('jump,title,icon,data_name as name')
        ->field('title,icon,data_name as name,jump')
        ->select();
      return $result;
    }

}


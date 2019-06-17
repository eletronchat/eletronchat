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


    /**
     * 规则树
     *
     */
    public function allToTree()
    {
        $result = self::
          field(['title'=>'name','id'=>'value', 'pid', 'concat(path,"-",id)'=>'fullpath'])
          ->order('fullpath') 
          ->select();
      return $result;
    }


    
}


<?php 
namespace app\api\controller\v1;

use app\api\model\AuthRule;

class Module extends Base
{
    /**
     * 获取功能模块列表
     * @url  /aip/v1/moduleList
     * @http get
     * @return  功能模块列表
     */
    public function list()
    {
      $side_menu = (new AuthRule())->getSideMenu();
      //dump($side_menu);exit;
      return [
        'code' => 0,
        'msg'  => '',
        'data' => $side_menu  
      ]; 
    }
}


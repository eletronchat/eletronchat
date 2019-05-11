<?php 
namespace app\api\controller\v1;

use think\Controller;
  
class Module extends Controller
{
    /**
     * 获取功能模块列表
     * @url  /aip/v1/moduleList
     * @http get
     * @return  功能模块列表
     */
    public function list()
    {
      return [
        'code' => 0,
        'msg'  => '',
        'data' => [
          0 => [
          "name" => "get",
          "title"=> "授权",
          "icon"=> "layui-icon-auz",
          "jump"=> "system/get" 
        ]
        ]  
      ]; 
    }
}


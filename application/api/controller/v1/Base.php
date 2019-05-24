<?php 
/**
 * api 接口基类
 * @name    wuchuheng
 * @email   wuchuehng@163.com
 * @data    2019/05/13
 * @blog    www.wuchuheng.com
 */
namespace app\api\controller\v1;

use think\Controller;
 
class Base extends Controller
{
    /**
     * 返回成功消息
     * @style   array  数据返回风格格式，默认layui风格
     */  
    public function successMessage($mix = [])
    {
      //dtree 风格
      if (array_key_exists('style', $mix) && $mix['style'] === 'dtree') 
      {
        return ['status'=>['code' => 200, 'massage' => 'success', ], 'data' => $mix['data'] ];
      }
      //默认layui风格
      $data = array_key_exists('data', $mix) ? $mix['data'] : $mix;
      return [
        'errorCode' => 0,
        'msg'  => 'sucess',
        'data' => $data
      ]; 
    }
}




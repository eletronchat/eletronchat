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
use think\Db;
use think\facade\Env;

 
class Base extends Controller
{
    public function initialize()
    {
      if (Env::get('status', 'null') === 'dev') {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $hash = time();
        $class = __NAMESPACE__ . '\\' . substr(request()->controller(), 3);
        $reflector = new \reflectionClass($class);
        foreach ($reflector->getMethods() as $v){
          if (strtolower($v->name) === request()->action()) {
              $commend = explode("* ", $v->getDocComment())[1];
              $rule = request()->module() . '/' . request()->controller() . '/' . request()->action();
              $hasData = Db::name('auth_rule')->where('name', $rule)->find();
              $isSave = Db::name('tmp')->where('title', $commend)->where('name', $rule)->find();
              if (!$hasData && !$isSave) {
                Db::name('tmp')->insert(['title'=>$commend, 'name'=>$rule]);
              }
          }
        }
      }
    }


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
      $result =  [
        'errorCode' => 0,
        'msg'  => 'sucess',
        'data' => $data
      ]; 
      //自定义添加自定义字段
      if (array_key_exists('data', $mix)) {
          unset($mix['data']);
          $result = array_merge($mix, $result);
      }
      return $result;
    }
}




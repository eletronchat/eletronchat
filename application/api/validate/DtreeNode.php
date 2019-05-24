<?php 
/**
 * Dtree   客服管理api验证规则 
 * @author wuchuheng 
 * @email  wuchuheng@163.com
 * @date   2019-05-17
 */
namespace app\api\validate;

use think\facade\Request;
use app\lib\exception\ErrorException;

class DtreeNode extends Base
{
    protected $rule = [];
    protected $message = [];

     public function __construct()
     {
       switch(Request::method()) {
           case 'GET' : 
             $this->get();
             break;
           case 'POST' :
             $this->post();
             break;
           case 'PUT' :
             $this->put();
             break;
           case 'DELETE' :
             $this->delete();
             break;
           default:
             //非法访问，直接K掉 :xxx其实如果开启强制也就没有必要留下这个异常了 
             throw new ErrorException(
               [
                 'errorCode' => 50001,
                 'msg'       => ' illegally accessed',
               ] 
             ); 
       }
     }


    /**
     *读取验证规则
     *
     */
     protected function get()
     {
         $this->rule   = [];
        $this->message = [];
     }


    /**
     * 添加验证规则
     *
     */
     protected function post() 
     {
       $this->rule = [
         'parentId'    => 'require|number',
         'addNodeName' => 'require'
       ];
       $this->message = [
         'parentId.require'  => '节点parentId必须有',
         'parentId.number'   => '节点parentId为数字类型',
         'addNodeName'       => '节点名称addNodeName必须有'
       ];
     }


    /**
     * 编辑验证规则
     *
     */
     protected function put()
     {
         $this->rule = [
           'parentId'    => 'require|number',
           'editNodeName' => 'require'
         ];
         $this->message = [
           'parentId.require'  => '节点parentId必须有',
           'parentId.number'   => '节点parentId为数字类型',
           'editNodeName'       => '修改的节点名称editNodeName必须有'
         ];
     }


     /**
     * 删除验证规则
     *
     */
     protected function delete()
     {
     
     }
     
}



<?php 
/**
 * 客服用户管理
 * @name    wuchuheng
 * @email   wuchuehng@163.com
 * @data    2019/05/13
 * @blog    www.wuchuheng.com
 */
namespace app\api\controller\v1;

use app\api\service\Role as RoleService;
use think\facade\Request;
use app\api\validate\DtreeNode;
use app\lib\exception\ErrorException;
 
class Role extends Base
{
    /**
     * 获取客服所有分类
     * @url     /api/v1/group
     * @http    get
     * @return  json
     **/ 
    public function getAllGroup()
    {      //验证拦截线
       (new DtreeNode())->gocheck(); 
        $hasdata = (new RoleService())->getAllUser();   
        if (!$hasdata) throw new ErrorException(); 
        return  parent::successMessage(['style'=>'dtree', 'data'=>$hasdata]);
    }


    /**
     * 增加节点
     * @url     /api/v1/group
     * @http    post
     * @return  json 
     */
    public function addNode()
    {
       //验证拦截线
       (new DtreeNode())->gocheck(); 
       //添加子节点
       $result = (new RoleService())->addGroup();
       if ($result->id) {
         $id = $result->id;
         return  parent::successMessage(['style'=>'dtree', 'data'=>['nodeId'=>$id]]);
       }
    }

    
    /**
     * 修改节点  
     * @url    /api/v1/group
     * @http   put 
     * @return json
     */
     public function editNode()
     {
       (new DtreeNode())->gocheck(); 
       $is_edit = (new RoleServce())->editGroup();
         dump(Request::param());
     }


    /**
     * 获取单组用户详情
     * @id=0 代表所有用户 
     * @id=-0 代表未分组用户
     *
     */
    

}


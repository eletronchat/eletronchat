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
    { 
        (new DtreeNode())->scene('get')->gocheck(); 
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
      (new DtreeNode())->scene('post')->gocheck(); 
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
        (new DtreeNode())->scene('put')->gocheck(); 
        $isSave= (new RoleService())->editGroup();
        if (!$isSave) throw new ErrorException(['msg' => 'to update data faild']); 
        return  parent::successMessage();
     }

    
    /**
      * 删除节点
     * @url    /api/v1/group
     * @http   delele
     * @return json  
     */
    public function delNode()
    {
        (new DtreeNode())->scene('delete')->gocheck(); 
        $isDel = (new RoleService)->delGroup();
        if (!$isDel) {
            throw new ErrorException('删除失败，内部错误');
        } else {
            return parent::successMessage();
        }
    }
    

    /**
    *  获取权限角色列表
    *
    */
    public function getRoleList()
    {
        $hasData = (new RoleService())->getRoleList();
        if($hasData->isEmpty()) {
          throw new ErrorException([
            'msg'=>'没有权限角色分组，请先添加',
            'code' => 500
          ]);
        } else {
            return parent::successMessage($hasData);
        }
    }
 
}


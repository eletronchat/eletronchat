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
use app\api\validate\DtreeAddNode;
 
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
        $data['data'] = (new RoleService())->getAllUser();
        $data['status'] = [
          'code'=> 200,
          'message' => 'success'
        ];
        return $data ;
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
       (new DtreeAddNode())->gocheck(); 
       //添加子节点
       $result = (new RoleService())->addGroup();
       if ($result->id) {
         $id = $result->id;
         return  parent::successMessage(['style'=>'dtree', 'data'=>['nodeId'=>$id]]);
       }
    }

    
    /**
     * 获取单组用户详情
     * @id=0 代表所有用户 
     * @id=-0 代表未分组用户
     *
     */
    

}


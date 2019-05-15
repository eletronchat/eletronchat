<?php 
/* 
 *  Role 控制器服务层
 *  @author wuchuheng
 *  @data 2019/05/16
 *  @email wuchuheng@163.com
 *  @blog  www.wuchuheng.com
 */
namespace app\api\service;

use app\api\model\Member; 

class Role extends Base
{
    /**
     * 获取统计用户组信息
     * @return obj
     */
    public function getAllUser()
    {
        $count = Member::count();
        $data['title']    = "所有{$count}";
        $data['id']       = 0;
        $data['parentId'] = 0;
        $data['spread']   = true;
        return $data;
    } 
}



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
use app\api\service\Base;
use think\Db;
use think\facade\Request;
use app\api\model\MemberGroup;

class Role extends Base
{
    /**
     * 获取客服组数据树
     * @return obj
     */
    public function getAllUser()
    {
        $count           = Member::count();
        $count_no_count  = (new Member())->countNotBelong();
        $memberGroup     = (new MemberGroup())->getGroup();
        $otherNode       = [
            'title'     => "未分组({$count_no_count})",
            'id'        => -1,
            'parentId'  => 0-1
          ];
        $subNode = $this->_arrToTree($memberGroup->toArray());
        //返回，更新子节点
        if (Request::get('nodeId')) return $subNode;
        $subNode[] = $otherNode; 
        $data[] = [
          'title'    => "所有({$count})",
          'id'       => 0,
          'spread'   => true,
          'parentId' => 0,
          'children' => $subNode
        ];
        return $data;
    } 


    /**
     * 添加客服组
     * @return  boolean    处理结果
     */
    public function AddGroup() 
    {
        $data['name'] = Request::param('nodeName');
        $pid = Request::param('parentId');
        if ($pid) {
          $parentNode =(new MemberGroup())->where("id = {$pid}")->field('id,path')->find();
          $data['path'] = $parentNode->path . '-' . $parentNode->id;
          $data['pid']  = $parentNode->id;
        }
        $data['name'] = Request::param('addNodeName');
        $isSave = (new MemberGroup())->create($data);
        return $isSave;
    }


    /**
     * 将数组遍历为数组树 
     * @arr     有子节点的目录树
     * @tree    遍历赋值的树
     * @return  array   
     *
     */ 
    protected function _arrToTree($items, $pid = 'pid')
    {
         $map  = [];
         $tree = [];   
         foreach ($items as &$it){
           $el = &$it; 
           $el['title'] = $el['title'] . "(" .$el['count']. ")";
           $el['parentId'] = $el['pid'];
           unset($el['path']);
           unset($el['name']);
           $map[$it['id']] = &$it; }  //数据的ID名生成新的引用索引树
         foreach ($items as &$it){
           $parent = &$map[$it[$pid]];
           if($parent) {
             $parent['children'][] = &$it;
           }else{
             $tree[] = &$it;
           }
         }
         return $tree;
    }
}


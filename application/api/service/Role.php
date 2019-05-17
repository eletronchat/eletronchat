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
      dump(Request::param());
    }

    /**
     * 将数组遍历为数组树 
     * @arr     有子节点的目录树
     * @tree    遍历赋值的树
     * @return  array   
     *
     */ 
    protected function _arrToTree($arr)
    {
      foreach($arr as $k=> $v) {
        array_shift($arr);
        $node['id'] = $v['id'];
        $node['title'] = $v['title'] . "(" . $v['count'] . ")";
        $node['parentId'] = str_replace('-', ' ', substr($v['fullpath'], 1));
        if (count($arr) === 0) return [$node];
        $nextNode = reset($arr);
        if ($v['fullpath'] == '0-0') {
            if ($nextNode['fullpath'] !== '0-0') {
              $node['children'] = $this->_arrToTree($arr);
            }
            $result[] = $node;
        } else {
          if (strpos($nextNode['fullpath'], $v['fullpath']) > 0) {
              $node['children'] = $this->_arrToTree($arr);
          }
          return $node; 
        } 
      }
    }
  
}



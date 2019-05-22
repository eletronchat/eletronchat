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
        $data['name'] = Request::param('nodeName');
        $pid = Request::param('parentId');
        $parentNode =(new MemberGroup())->where("id = {$pid}")->field('id,path')->find();
        $data['path'] = $parentNode->path . '-' . $parentNode->id;
        $data['pid']  = $parentNode->id;
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
    protected function _arrToTree($arr)
    {
      $result = [];
      foreach($arr as $v) {
        $el = [
          'id'       => $v['id'],
          'title'    => $v['title'] . "(" . $v['count'] . ")",
          'parentId' => $v['pid'],
          'fullpath' => $v['fullpath']
        ];
        if ($v['pid'] === 0) {
            $result[$el['id']] = $el;
        } else {
            $key_path = explode('-', $v['path']);
            unset($key_path[0]);
            $eval = '$result';
            foreach($key_path as $key) {
              (int)$key;
              $eval .= '[' . $key . '][\'children\']';
            }
            //登记节点路径
            $chil_arr_path[] = $eval. '[' . $v['id'] .']';
            $eval .= '[' . $v['id'] .'] = $el;';
            eval($eval);
        }
      }
      //更新登记节点路径集
       foreach ($chil_arr_path as $arr_path) {
           $arr_path = array_shift($chil_arr_path); //引用循环内变量
           $parent_node = explode("['children']", $arr_path);
           array_pop($parent_node);
           $parent_node = implode("['children']", $parent_node) . "['children']";
           eval('$tmp = ' . $arr_path . ';');
           eval('unset(' . $arr_path . ');'); //剔除出登记表
           eval('$parent_data = ' . $parent_node . ';');
           //计算新节点编号
           if (isset($parent_node['id'])) {
               $node_num["$parent_node"] = isset($node_num["{$parent_node}"]) ? ++$node_num["{$parent_node}"] : count($parent_node);
           } else {
               $node_num["{$parent_node}"] = isset($node_num["{$parent_node}"]) ? ++$node_num["{$parent_node}"] : 0;
           }
           //拼接为新的节点路径
           $new_node = $parent_node. '[' . $node_num["{$parent_node}"] . ']';
           eval($new_node . '= $tmp;');
           //更新节点登记表集合基路径
           $pattern = '/' . preg_quote($arr_path) . '/';
           $chil_arr_path  = preg_replace($pattern, $new_node, $chil_arr_path);
       }
      $result = array_values($result);
      return $result;
    }
  
}



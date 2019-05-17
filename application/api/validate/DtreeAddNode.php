<?php 
/**
 * Dtree   目录树添加节点参数规则定义 
 * @author wuchuheng 
 * @email  wuchuheng@163.com
 * @date   2019-05-17
 */
namespace app\api\validate;

class DtreeAddNode extends Base
{
    protected $rule = [
      'parentId'    => 'require|number',
      'addNodeName' => 'require'
    ];

    protected $message = [
      'parentId.require'  => '节点id必须有',
      'parentId.number'   => '节点id为数字类型',
      'addNodeName'       => '新增加的节点名称必须有'
    ];
}



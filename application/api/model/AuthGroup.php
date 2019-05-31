<?php 
namespace app\api\model;

class AuthGroup extends Base
{
     /**
     *  添加dtree字段 parentId
     *
     */ 
    public function getParentIdAttr($value, $data)
    {
        return 0;
    }


}



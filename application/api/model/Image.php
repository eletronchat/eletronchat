<?php 
namespace app\api\model;

class Image extends Base
{
    /**
    * 图片路径修改
    *
    *
    */
  public function getUrlAttr($value, $data)
  {
      return $value;
  }
}


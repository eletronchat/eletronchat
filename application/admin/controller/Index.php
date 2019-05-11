<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    /**
     * 左则菜单栏
     *
     */
    public function index()
    {
        return $this->fetch();
    }


    /**
     * 首页
     *
     */
    public function layout()
    {
        return $this->fetch(); 
    }
}

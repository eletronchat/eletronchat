<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    /**
     * 后台主框架
     *
     */
    public function index()
    {
        return $this->fetch();
    }


    /**
     * 左则菜单栏
     * @http    ajax.get
     * @return  html
     */
    public function layout()
    {
        return response($this->fetch());
    }


    /*
     * 主页
     * @http    ajax.get
     * @return  html
     */
    public function home()
    {
        return response($this->fetch());
    }



}

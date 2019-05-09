<?php 
namespace app\index\controller;

use think\Controller;
use auth\Auth;

class Test extends Controller
{
    public function index()
    {
      (new Auth())->hello();
    }
}


<?php
namespace app\index\controller;

use app\api\model\Member;

class Index
{
    public function index()
    {
        
      return '<html>
      <body>
          <p>登录</p>
          <p>后台</p>
          <p><a href="/admin">adminin</a></p>
      </body>
</html>';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}

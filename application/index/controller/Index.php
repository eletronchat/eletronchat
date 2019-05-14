<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        
      return '<html>
      <body>
          <p>登录</p>
          <p>登录</p>
          <p><a href="/admin">adminin</a></p>
      </body>
</html>';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}

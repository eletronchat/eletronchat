<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//api 菜单列表
Route::get('api/:version/moduleList', 'api/:version.module/list');
//api 客服管理
Route::Group('api/:version', function(){
  //客服分类目录树
  Route::get('/group', 'api/:version.Role/getAllGroup');
  //客服分类目录树
  Route::post('/group', 'api/:version.Role/addNode');
});

return [

];

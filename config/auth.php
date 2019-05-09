<?php 

/**
 * 用户权限配置
 *
 */

return [
  'auth_on'           => 1, //权限开关
  'auth_type'         => 1, //认证方式1实时认证，2登录认证
  'auth_group'        => 'auth_group', //用户组表名
  'auth_group_access' => 'auth_group_acess', //用户-用户组关系表
  'auth_rule'         => 'auth_rule', // 权限表
  'auth_user'         => 'member' //用户信息表
];

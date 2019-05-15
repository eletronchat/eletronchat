/**
 *  角色业务处理模块
 *  @name   wuchuheng
 *  @date   2019/05/14
 *  @email  wuchuheng@163.com
 *  @blog   www.wuchuheng.com
 */


;layui.define(function(e) {
  //目录树
  layui.use(['layer', 'table', 'dtree'], function(){
    var layer = layui.layer,
      table = layui.table,
      dtree = layui.dtree,
      $ = layui.$;
    dtree.render({
      elem: "#tree",
      url: layui.cache.rest_url+"/group",
      method: "get",
      icon: "2",
      initLevel: "1",
      toolbar: true,
      toolbarStyle: {
        "title": "客服组"
      }
    });
  });

 //样式 
  layui.use('jquery', function(){
    var $ = layui.jquery;
    $('#tree').css("height", $(window).height()*0.8+"px");
  
 });

  //输入接口
  e("role", {});
});


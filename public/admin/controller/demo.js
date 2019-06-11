/**
 *  只是一个demo模块
 *  @name   wuchuheng
 *  @date   2019/05/14
 *  @email  wuchuheng@163.com
 *  @blog   www.wuchuheng.com
 */

;layui.define(function(e) {
  layui.use(['layer', 'table', 'dtree'], function(){
    var layer = layui.layer,
      table = layui.table,
      dtree = layui.dtree,
      $ = layui.$;
    dtree.render({
      elem: "#commonTree4",
      url: "http://electronchat.com/json/case/commonTree4.json",
      method: "get",
      icon: "2",
      initLevel: "1"
    });


  });
  e("demo", {})
});


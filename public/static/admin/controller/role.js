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
      },
      toolbarFun: {
        //增加节点
        addTreeNode: function(treeNode, $div){
          $.ajax({
            type: "post",
            data: treeNode,
            url: layui.cache.rest_url+"/group",
            success: function(result){
              console.log(result); 
              //DTree1.changeTreeNodeAdd(treeNode.nodeId); // 添加成功，返回ID
              //DTree1.changeTreeNodeAdd(true); // 添加成功
              //DTree1.changeTreeNodeAdd(result.data); // 添加成功，返回一个JSON对象
              DTree1.changeTreeNodeAdd("refresh"); // 添加成功，局部刷新树
            },
            error: function(){
              //DTree1.changeTreeNodeAdd(false); // 添加失败
            }
          });
        }, 

       }
    });
  });
  
 //样式 
  layui.use('jquery', function(){
    var $ = layui.jquery;
    $('#tree').css("height", $(window).height()*0.75+"px");
  
 });
  
  

  //输入接口
  e("role", {});
});


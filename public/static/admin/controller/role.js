/**
 *  角色业务处理模块
 *  @name   wuchuheng
 *  @date   2019/05/14
 *  @email  wuchuheng@163.com
 *  @blog   www.wuchuheng.com
 */


;layui.define(function(e) {
  //目录树
  layui.use(['admin', 'form', 'layer', 'table', 'dtree'], function(){
    var layer = layui.layer,
        table = layui.table,
        dtree = layui.dtree,
        $     = layui.$,
        form  = layui.form,
        admin = layui.admin,
        element = layui.element,
        router = layui.router();

  element.render();
     var DTree = dtree.render({
      elem: "#tree",
      url: layui.cache.rest_url+"/group",
      method: "get",
      icon: "2",
      initLevel: "1",
      toolbar: true,
      skin: "zdy",
      toolbarStyle: {
        "title": "客服组"
     },
     done: function(data, obj){
       $("#search_btn").unbind("click");
       $("#search_node").click(function(){
         var value = $("#searchInput").val();
         if(value){
           var flag = DTree.searchNode(value); // 内置方法查找节点
           if (!flag) {layer.msg("该名称节点不存在！", {icon:5});}
         } else {
           DTree.menubarMethod().refreshTree(); // 内置方法刷新树
         }
       });
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
              DTree.changeTreeNodeAdd("refresh"); // 添加成功，局部刷新树
            },
            error: function(result){
              DTree.changeTreeNodeAdd(false); // 添加失败
              layer.msg(result.msg);
            }
          });
        }, 
        //修改节点
        editTreeNode: function(treeNode, $div){
          $.ajax({
            type: "PUT",
            data: treeNode,
            url: layui.cache.rest_url+"/group",
            success: function(result){
              DTree.changeTreeNodeEdit(true);
            },
            error: function(){
              layer.msg(result.msg);
            }
          });
        },
        //删除节点
        delTreeNode: function(treeNode, $div){
          $.ajax({
            type: "DELETE",
            data: treeNode,
            url: layui.cache.rest_url+"/group",
            success: function(result){
              DTree.changeTreeNodeDel(true); // 删除成功
            },
            error: function(result){
              layer.msg(result.responseJSON.msg, {icon: 2});
            }
          });
        }
       },
       //iframe 加载

    });
    //dtree  单击事件
    // 绑定节点的单击事件
    dtree.on("node('tree')", function(obj){
        rend_form(); 
    })
    //树右侧内容
    function rend_form() {
      //添加客服业务
      var active = {
        addmember: function(){
          admin.popup({
            title: '添加客服'
            ,shade: 0
            ,anim: -1
            ,area: ['690px', '700px']
            ,id: 'layadmin-layer-skin-test'
            ,skin: 'layui-anim layui-anim-upbit'
            ,content: $('#addmember-dom')
            ,btn: ['添加']
          })
        }
      };
      $('.addmember').on('click', function(){
        var type = $(this).data('type');
        active[type] && active[type].call(this);
      });
      form.render(); 
    }
    
    rend_form();//上表单渲染

  });
  
 //样式 
  layui.use('jquery', function(){
    var $ = layui.jquery;
    $('#tree').css("height", $(window).height()*0.65+"px");
    $('.layadmin-iframe').css("height", $(window).height()*0.81+"px");
  
 });
  
  
  //输入接口
  e("role", {});
});


/**
 *  角色业务处理模块
 *  @name   wuchuheng
 *  @date   2019/05/14
 *  @email  wuchuheng@163.com
 *  @blog   www.wuchuheng.com
 */


;layui.define(function(e) {
  //目录树
  layui.use(['admin', 'form', 'layer', 'table', 'dtree', 'upload'], function(){
    var layer = layui.layer,
        table = layui.table,
        dtree = layui.dtree,
        upload= layui.upload,
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
        //rend_form(); 
    })
    form.render('select', 'search');//搜索表单渲染


    //****** 添加客服表单处理 s******//
    //表单弹出层
    $('.addmember').on('click', function(){
      admin.popup({
        title: '添加客服'
        ,shade: 0
        ,anim: -1
        ,area: ['700px', '550px']
        ,id: 'layadmin-layer-skin-test'
        ,skin: 'layui-anim layui-anim-upbit'
        ,content: $('#addmember-dom')
        ,btnAlign: 'c'
        ,scrollbar: false
        ,tips: [1, '#c00']
      });

    });
    //头像上传
    var uploadInst = upload.render({
      elem: '#upload-img' //绑定元素
      ,url: '/upload/' //上传接口
      ,auto: false
      ,bindaction: "#submit-buttom"
      ,choose: function(obj){
        //头像预览
        obj.preview(function(index, file, result){
          $('img').attr('src', result);
        });
      }
      ,done: function(res){
        //上传完毕回调
      }
      ,error: function(){
        //请求异常回调
      }
    });
    //初始化角色值
    admin.req({
      url: layui.cache.rest_url + "/roleList",
      type: 'GET',
      done: function(res){
        for(var i in res.data) {
          var html = "<option value='"+res.data[i].id+"'>"+res.data[i].title+"</option>";
          $('select[name=select_role]').append(html);
        }
        form.render('select', 'add-member-form');//添加客服表单select渲染
      }
    });
    //客服分组选择回调
    form.on('select(select-group)', function(data){
      console.log(data);
    });
    layer.ready(function(){
      var DTree = dtree.render({
        elem: "#slTree",
        method: 'get',
        url: layui.cache.rest_url+"/group",
        icon: "2",
        accordion:true
      });
      $("#city").on("click",function(){
        $(this).toggleClass("layui-form-selected");
        $("#test").toggleClass("layui-show layui-anim layui-anim-upbit");
        $(".isshow").toggle();
      });
      dtree.on("node(slTree)", function(obj){
        $("#input_city").val(obj.param.context);
        $("#city").toggleClass("layui-form-selected");
        $("#test").toggleClass("layui-show layui-anim layui-anim-upbit");
      });
    });
    //验证
    form.verify({
      account: function(value){
        if(value.length < 5){
          return '帐号字符不少于6个字符';
        }
      }
      ,passwd: [
        /^[\S]{6,12}$/
        ,'密码必须6到12位，且不能出现空格'
      ]
      ,username: function(value){
        if(value.length < 2){
          return '请输入全名';
        }
      }
      ,phone: function(value){
        if(value.length != 11){
          // :xxx 您的手机号少（多）写了n位
          return '请输入11位手机号码';
        }
      } 
      ,select_role: function(value){
          if(value === '') {
              return '请选择权限角色';
          }
          
      }
    });
    //提交
    form.on('submit(add-member-form)', function(data){
      var memberData = data.field;
      if ( memberData.file !== '' ) 
          memberData.file = $('#preview').attr('src');
    admin.req({
      url: layui.cache.rest_url + "/member",
      type: 'POST',
      data: memberData,
      done: function(res){
          console.log(res);
      }
    });
      return false;
    });
    //************ 添加客服表单 e ***********//
    

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

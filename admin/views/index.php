<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>layuiAdmin pro - 通用后台管理模板系统（单页面专业版）</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="static/layui/css/layui.css" media="all">
    <link id="layuicss-layer" rel="stylesheet" href="static/layui/css/modules/layer/default/layer.css" media="all">
    <link id="layuicss-layuiAdmin" rel="stylesheet" href="static/css/admin.css" media="all">
</head>
<body layadmin-themealias="default" class="layui-layout-body">


<div class="layui-side layui-side-menu">
    <div class="layui-side-scroll">
        <div class="layui-logo" lay-href="" style="line-height: 60px;text-align: center"><span>后台管理</span></div>
        <ul class="layui-nav layui-nav-tree" lay-filter="test">
            <li class="layui-nav-item layui-nav-itemed"><a href="javascript:;">文章管理</a></li>
            <li class="layui-nav-item"><a href="/admin/admin.php?action=publish_news">发布文章</a></li>
        </ul>
    </div>
</div>


<div class="layui-body" id="LAY_app_body">
    <div class="layadmin-tabsbody-item layui-show">
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">新闻列表</div>
                        <div class="layui-card-body">
                            <table class="layui-hide" id="news_list" lay-filter="news_list_table"></table>
                            </table>
                            <style>.laytable-cell-11-0-0{ width: 48px; }.laytable-cell-11-0-1{ width: 80px; }.laytable-cell-11-0-2{ width: 120px; }.laytable-cell-11-0-3{ width: 80px; }.laytable-cell-11-0-4{ width: 80px; }.laytable-cell-11-0-5{ width: 100px; }.laytable-cell-11-0-6{  }</style>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script src="static/layui/layui.js"></script>
    <script id="toolBar">
        <div class="layui-btn-group">
            <a href="/admin/admin.php?action=edit_news&news_id={{d.id}}" target="_blank" type="button" class="layui-btn layui-btn-sm"> <i class="layui-icon">&#xe642;</i></a>
            <button type="button" lay-event="del" class="layui-btn layui-btn-sm action_del"> <i class="layui-icon">&#xe640;</i></button>
        </div>
    </script>
        <script>
            layui.use(['table','jquery','layer'], function(){
                var table = layui.table;
                var jq=layui.jquery;
                var layer=layui.layer;
                table.render({
                    elem: '#news_list'
                    ,url:'/admin/admin.php?action=news_list'
                    ,title: '新闻列表'
                    ,totalRow: true
                    ,cols: [[
                        {field:'id', title:'ID', width:60, fixed: 'left', unresize: true, sort: true, totalRowText: '合计'}
                        ,{field:'title', title:'标题', width:340}
                        ,{field:'dkeys', title:'关键字', width:200, totalRow: true}
                        ,{field:'digst', title:'描述', width:500, totalRow: true}
                        ,{field:'category_name', title:'类别', width:80}
                        ,{title:"操作",align:'center',width:135,height:80,toolbar:'#toolBar'}
                    ]]
                    ,page: true
                    ,limits:20,
                    limit:20
                });
                table.on('tool(news_list_table)',function (obj) {
                   if (obj.event=='del'){
                       let data=obj.data;
                       layer.confirm('确认删除改行数据吗？', function(index){
                           jq.ajax({url:"/admin/admin.php?action=del_news&news_id="+data.id,
                               type:'get',
                               dataType:'json',
                               success:function (resp) {
                                   if (resp.code==0){
                                       obj.del();
                                       layer.close(index);
                                       layer.msg("删除成功");
                                   }else{
                                       layer.close(index);
                                       layer.msg("删除失败");
                                   }
                               }});
                       });
                   }
                });


            });
        </script>
    </div>
</div>
</body>
</html>
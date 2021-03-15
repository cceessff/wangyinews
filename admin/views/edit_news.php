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
            <li class="layui-nav-item layui-nav-itemed"><a href="/admin/admin.php">文章管理</a></li>
            <li class="layui-nav-item"><a href="/admin/admin.php?action=publish_news">发布文章</a></li>
        </ul>
    </div>
</div>
<div class="layui-body" id="LAY_app_body">
    <div class="layadmin-tabsbody-item layui-show">
        <div class="layui-fluid">
            <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                <legend>编辑新闻</legend>
            </fieldset>
            <form class="layui-form">
                <input type="hidden" name="id" value="<?php echo $news['id']?>">
                <div class="layui-form-item">
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" value="<?php echo htmlspecialchars($news['title'])?>" lay-verify="require|title" autocomplete="off" placeholder="请输入标题" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">关键字</label>
                    <div class="layui-input-block">
                        <input type="text" name="dkeys" value="<?php echo htmlspecialchars($news['dkeys'])?>" lay-verify="required" lay-reqtext="关键字必须填写" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">发布时间</label>
                    <div class="layui-input-block">
                        <input type="text" id="ptime" name="ptime"  lay-verify="required" lay-reqtext="发布时间必须填写" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">描述</label>
                        <div class="layui-input-block">
                            <textarea type="text" name="digst"  autocomplete="off" class="layui-textarea"><?php echo htmlspecialchars($news['digst'])?></textarea>
                        </div>
                </div>
                <div class="layui-form-item layui-form-text" >
                        <label class="layui-form-label">内容</label>
                        <div class="layui-input-block">
                            <div style="padding: 0" type="text" id="content" name="content" lay-verify="" autocomplete="off" class="layui-textarea"><?php echo $news['content']?></div>
                        </div>

                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="update">立即提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="static/js/wangEditor.js"></script>
<script src="static/layui/layui.js"></script>
<script type="text/javascript">
    var E = window.wangEditor
    var editor = new E('#content');
    editor.create();
    layui.use(['layer','laydate','form','jquery'],function () {
        var layer=layui.layer;
        var laydate=layui.laydate;
        var form=layui.form;
        var jq=layui.jquery;
        laydate.render({
            elem:'#ptime',
            type:'datetime',
            value: '<?php echo htmlspecialchars(date('Y-m-d H:i:s',$news['ptime']))?>'
            ,isInitValue: true
        });
        form.on('submit(update)',function (data) {
            data.field.content=editor.txt.html();
            console.log('sssssss');
            jq.ajax({
                url:"/admin/admin.php?action=update_news",
                type:'post',
                data:data.field,
                dataType:'json',
                success:function (resp) {
                    if (resp.code!=0) {
                        layer.msg("更新失败");
                    }else{
                        layer.msg("更新成功");
                    }
                },
            });
            return false;
        });



    });
</script>
</body>
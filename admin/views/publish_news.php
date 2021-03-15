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
            <li class="layui-nav-item"><a href="/admin/admin.php">文章管理</a></li>
            <li class="layui-nav-item layui-nav-itemed"><a href="javascript:;">发布文章</a></li>
        </ul>
    </div>
</div>
<div class="layui-body" id="LAY_app_body">
    <div class="layadmin-tabsbody-item layui-show">
        <div class="layui-fluid">
            <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                <legend>添加新闻</legend>
            </fieldset>
            <form class="layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" lay-verify="require|title" autocomplete="off"
                               placeholder="请输入标题" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">关键字</label>
                    <div class="layui-input-block">
                        <input type="text" name="dkeys" lay-verify="required" lay-reqtext="关键字必须填写" placeholder="请输入"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">发布时间</label>
                    <div class="layui-input-block">
                        <input type="text" id="ptime" name="ptime" lay-verify="required" lay-reqtext="发布时间必须填写"
                               placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">新闻类型</label>
                    <div class="layui-input-block">
                        <select name="category" lay-filter="aihao">
                            <?php foreach ($ADMIN_CONFIG['categorys'] as $key=>$val):?>
                                <option value="<?php echo $key ?>"><?php echo $val ?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="layui-upload" style="margin-left: 115px">
                    <button type="button" class="layui-btn" id="thumb">封面图片</button>
                    <div class="layui-upload-list">
                        <img style="width: 95px;height: 95px;" class="layui-upload-img" id="thumb_img">
                        <p id="demoText"></p>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">描述</label>
                    <div class="layui-input-block">
                        <textarea type="text" name="digst" autocomplete="off" class="layui-textarea"></textarea>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">内容</label>
                    <div class="layui-input-block">
                        <div style="padding: 0" type="text" id="content" name="content" lay-verify="" autocomplete="off"
                             class="layui-textarea"></div>
                    </div>

                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" id="publish" class="layui-btn" lay-submit="" lay-filter="publish">立即提交</button>
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
    editor.customConfig.uploadImgServer = '/admin/admin.php?action=upload_img&type=image';
    editor.customConfig.uploadImgMaxSize = 2 * 1024 * 1024;
    editor.customConfig.uploadFileName='image';
    editor.customConfig.uploadImgHooks={
        success: function (xhr, editor, result) {
            // 图片上传并返回结果，图片插入成功之后触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
        },
        fail: function (xhr, editor, result) {
            // 图片上传并返回结果，但图片插入错误时触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
        },
        error: function (xhr, editor) {
            // 图片上传出错时触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象
        },
        timeout: function (xhr, editor) {
            // 图片上传超时时触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象
        },
    };
    editor.create();
    layui.use(['layer', 'laydate', 'form', 'jquery', 'upload'], function () {
        var layer = layui.layer;
        var laydate = layui.laydate;
        var form = layui.form;
        var jq = layui.jquery;
        var upload = layui.upload;
        laydate.render({
            elem: '#ptime',
            type: 'datetime',
        });
        upload.render({
            elem: "#thumb",
            url: "/admin/admin.php?action=upload_img&type=thumb",
            field:'thumb',
            size:100,
            accept:'images',
            before: function (obj) {
               layer.load();
            },
            done: function (res) {
                //如果上传失败
                if (res.code > 0) {
                    return layer.msg('上传失败');
                }
                jq('#thumb_img').attr('src',res.src);
                layer.closeAll('loading');
                //上传成功
            },


        });
        form.on('submit(publish)', function (data) {
            jq("#publish").addClass('layui-btn-disabled');
            data.field.content = editor.txt.html();
            data.field.img=jq("#thumb_img").attr("src");
            jq.ajax({
                url: "/admin/admin.php?action=publish_news",
                type: 'post',
                data: data.field,
                dataType: 'json',
                success: function (resp) {
                    if (resp.code != 0) {
                        jq("#publish").removeClass('layui-btn-disabled');
                        layer.msg(resp.msg);
                    } else {
                        layer.msg(resp.msg);
                    }
                },
            });
            return false;
        });


    });
</script>
</body>
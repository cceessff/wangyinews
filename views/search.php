<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <link rel="shortcut icon" href="/favicon.ico">

    <meta http-equiv="Content-Language" content="zh-CN">
    <meta name="keywords" content="<?php echo $keywords;?>">
    <meta name="description" content="<?php echo $description ?>">
    <title><?php echo $site_title; ?></title>
    <link rel="stylesheet" rev="stylesheet" href="/static/css/style.css" type="text/css" media="all">
    <link rel="stylesheet" rev="stylesheet" href="/static/css/font-awesome.min.css" type="text/css" media="all">
    <script src="/static/js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script src="/static/js/jquery.SuperSlide.2.1.1.js" type="text/javascript"></script>
    <?php echo $js;?>
</head>
<body>
<div class="sjwu head">
    <div class="zh">
        <h1 class="logo fl">
            <a href="<?php echo $site_url; ?>" title="<?php echo $site_name; ?>">
                <img src="/static/img/logo.png" alt="<?php echo $site_name; ?>">
            </a>
        </h1>
        <span class="ss fl">
    <form onsubmit="return checkSearchForm()" method="post" action="/search.php">
      <input name="keyword" size="11" id="edtSearch" type="text">
      <button class="search-submit" id="btnPost" type="submit"><i class="fa fa-search"></i></button>

    </form> 
    </span>
        <div class="clear"></div>
    </div>
</div>
<div class="clearfix" id="nav"><a href="javascript:;" id="pull"><i class="fa fa-bars"></i></a>
    <ul class="clearfix zh">
        <li class=""><a href="<?php echo $site_url;?>">首页</a></li>
        <?php foreach (array_slice($categorys,0,5) as $k=>$category):?>
            <li class=""><a href="<?php echo $site_url."category/".$k;?>"><?php echo $category;?></a></li>
        <?php endforeach;?>
        <li class=""><a href="#">更多</a>
            <ul>
                <?php foreach (array_slice($categorys,5) as $k=>$category):?>
                    <li><a href="<?php echo $site_url."category/".$k;?>"><?php echo $category;?></a></li>
                <?php endforeach;?>
            </ul>
        </li>

    </ul>
    <a class="search-on" href="javascript:;"><i class="fa fa-search"></i></a>
    <div class="sous">
        <form onsubmit="return checkSearchForm()" method="post"
              action="/search.php">
            <input name="keyword" size="11" id="edtSearch" type="text">
            <button class="search-submit" id="btnPost" type="submit"><i class="fa fa-search"></i></button>
            <div class="clear"></div>
        </form>
    </div>
</div>
<div class="xia15 gao60"></div>
<div class="place zh"><i class="fa fa-home"></i> <a href="<?php echo $site_url;?>">首页</a>&nbsp;&gt;&nbsp;<a
            href="<?php echo $site_url."s/".$keyword_get;?>"><?php echo $keyword_get;?></a></div>
<div class="main zh">
    <div class="left fl">
        <ul class="list list-li3">
            <?php foreach ($news_list as $news):?>
                <li>
                    <a href="<?php echo $site_url."content/".$news['id'].".html"?>" class="list-tu">
                        <img src="<?php echo $news['image']?:"/static/img/timthumb.gif";?>" alt="<?php echo $news['title']?>">
                    </a>
                    <span class="ribbon">
                        <a href="<?php echo $site_url.$news['category_path'];?>" title="查看<?php echo $news['category_name'];?>下的更多文章"><?php echo $news['category_name'];?></a>
                    </span>
                    <div class="pd10">
                        <h3 class="i20">
                            <a href="<?php echo $site_url."content/".$news['id'].".html"?>"><?php echo $news['title']?></a>
                        </h3>
                        <p><?php echo $news['digst']?></p>
                        <small>
                            <span class="fr"><i class="fa fa-eye"></i> <?php echo $news['view_count']?></span><i class="fa fa-clock-o"></i> <?php echo date('Y-m-d',$news['ptime'])?>
                        </small>
                    </div>
                </li>
            <?php endforeach;?>
            <div class="clear"></div>
        </ul>
        <div class="pagebar">
            <?php echo $page_str;?>
        </div>
    </div>
    <div class="sjwu rigth fr">
        <dl class="function" id="divPrevious">
            <dt class="function_t">最新资讯</dt>
            <dd class="function_c">
                <ul>
                    <?php foreach ($new_news as $news):?>
                        <li><a href="<?php echo $site_url."content/".$news['id'].".html"?>"><?php echo $news['title'];?></a></li>
                    <?php endforeach;?>
                </ul>
            </dd>
        </dl>
        <dl class="function" id="divPrevious">
            <dt class="function_t">热门浏览</dt>
            <dd class="function_c">
                <ul>
                    <?php foreach ($hot_news as $hot):?>
                        <li><a href="<?php echo $site_url."content/".$hot['id'].".html"?>"><?php echo $hot['title'];?></a></li>
                    <?php endforeach;?>
                </ul>
            </dd>
        </dl>

        <dl class="function" id="divhottag">
            <dt class="function_t">热门标签</dt>
            <dd class="function_c">
                <div>
                    <?php foreach ($tags as $tag):?>
                        <a href="<?php echo $site_url."tags/".$tag;?>"
                           title="<?php echo $tag;?>" class="tags3"><?php echo $tag;?></a>
                    <?php endforeach;?>
                </div>
            </dd>
        </dl>
    </div>
    <!--ad-->
    <div class="clear"></div>
</div>
<div class="foot" id="footer">
    <div class="zh">
        <p>Powered By <a href="<?php echo $site_url;?>" title="<?php echo $site_name;?>" target="_blank"><?php echo $site_name;?></a></p>
        京ICP1234567-2号 | 统计代码
    </div>
</div>
<script src="/static/js/main.js" type="text/javascript"></script>
<script type="/static/js/sf_praise_sdk.js"></script>

</body>
</html>
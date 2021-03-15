<?php
/**
 * Created by PhpStorm.
 * User: Dylan.L
 * Date: 2019/5/25
 * Time: 8:49
 */
$CONFIG=include 'config.php';
include 'DB.php';
$db=new DB($CONFIG['db']);

$categorys=$CONFIG['categorys'];//导航
$news_list=$db->getNews(55);//获取最新新闻
$lunbo=$db->getLunbo();
$hot_news=$db->getHotNews();
$tags=array();//标签
foreach ($news_list as $k=>$v){
    $tagarr=explode(",",$v['dkeys']);
    foreach ($tagarr as $item){
            if (isset($tags[$item])){
                $tags[$item]+=1;
            }else{
                $tags[$item]=1;
            }
        }
}
arsort($tags);
$tags=array_slice($tags,0,50);
$tags=array_keys($tags);//最终标签

$site_name=$CONFIG['site_name'];
$site_url=$CONFIG['site_url'];
$keywords=$CONFIG['keywords'];
$description=$CONFIG['description'];
$js=isset($CONFIG['js'])?$CONFIG['js']:"";
header("Referrer-Policy: no-referrer");
include "views/index.php";












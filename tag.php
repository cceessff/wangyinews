<?php
/**
 * Created by PhpStorm.
 * User: Dylan.L
 * Date: 2019/5/27
 * Time: 13:26
 */
$tag_get = urldecode($_GET['tag']);


$CONFIG = include 'config.php';
include 'DB.php';

$site_name = $CONFIG['site_name'];
$site_url = $CONFIG['site_url'];
$categorys = $CONFIG['categorys'];//导航
$db = new DB($CONFIG['db']);
$tag=$tag_get;
$page=isset($_GET['page'])?(int)$_GET['page']:1;
$per_page=20;
$host=$_SERVER['HTTP_HOST'];

$news_list = $db->getNewsByTag($tag_get,$page,$per_page);
$total_count=$db->getNewsCountByTag($tag_get);
$total_page=ceil($total_count/20);
$page_str=page($page,$total_page,"tags","http://$host");
$site_title = $tag_get . "-" . $site_name;
$keywords = "$tag_get,$site_name";
$description = "关于{$tag_get}的资讯文章";

$new_news = $db->getNews(20);//获取最新新闻
$hot_news = $db->getHotNews();//获取最新新闻
$tags = array();//标签
foreach ($news_list as $k => $v) {
    $tagarr = explode(",",$v['dkeys']);
    if (is_array($tagarr)){
        foreach ($tagarr as $item) {
            if (isset($tags[$item])) {
                $tags[$item] += 1;
            } else {
                $tags[$item] = 1;
            }
        }
    }



}
arsort($tags);
$tags = array_slice($tags, 0, 30);
$tags = array_keys($tags);//最终标签
$page_str = '';
$js=isset($CONFIG['js'])?$CONFIG['js']:"";
$category_name = $tag_get;
header("Referrer-Policy: no-referrer");
include "views/tag.php";

function page($page = 1, $total_page, $category, $host)
{
    $result = '';
    $distance = $total_page - $page;
    $next_page = $page + 1;
    $pre_page = $page - 1;
    $pagenum = $distance > 5 ? 5 : $distance;
    if ($page == 1) {

        $result .= "<span class=\"page now-page\">{$page}</span>";
        for ($i = 1; $i < $pagenum; $i++) {
            $cpage = $page + $i;
            $result .="<a href=\"{$host}/tags/{$category}-{$cpage}\"><span class=\"page\">{$cpage}</span></a>";
        }
        if ($distance > 0) {
            $result .="<a href=\"{$host}/tags/{$category}-{$next_page}\"><span class=\"page\">››</span></a>";
        }
        return $result;
    } elseif ($page == $total_page) {
        $result .="<a href=\"{$host}/tags/{$category}-{$pre_page}\"><span class=\"page\">‹‹</span></a>";
        $start = $total_page - 3 > 0 ? $total_page - 3 : $total_page - 1;
        for ($i = $start; $i <= $total_page; $i++) {
            if ($i == $page) {
                $result .= "<span class=\"page now-page\">{$page}</span>";
            } else {
                if ($i<=$total_page){
                    $result .="<a href=\"{$host}/tags/{$category}-{$i}\"><span class=\"page\">{$i}</span></a>";
                }
            }
        }
        return $result;

    } else {
        $result .="<a href=\"{$host}/tags/{$category}-{$pre_page}\"><span class=\"page\">‹‹</span></a>";
        $start = $page - 2 > 0 ? $page - 2 : $page - 1;
        for ($i = $start; $i < $start +5; $i++) {
            if ($i == $page) {
                $result .= "<span class=\"page now-page\">{$page}</span>";
            } else {
                if ($i<=$total_page){
                    $result .="<a href=\"{$host}/tags/{$category}-{$i}\"><span class=\"page\">{$i}</span></a>";
                }
            }
        }
        $result .="<a href=\"{$host}/tags/{$category}-{$next_page}\"><span class=\"page\">››</span></a>";
        return $result;
    }
}

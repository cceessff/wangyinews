<?php
/**
 * Created by PhpStorm.
 * User: Dylan.L
 * Date: 2019/5/27
 * Time: 13:26
 */



$CONFIG = include 'config.php';
include 'DB.php';

$keyword_get = urldecode($_REQUEST['keyword']);
$page=isset($_GET['page'])?(int)$_GET['page']:1;
$site_name = $CONFIG['site_name'];
$site_url = $CONFIG['site_url'];
$categorys=$CONFIG['categorys'];
$db = new DB($CONFIG['db']);

$news_list = $db->getNewsByTitle($keyword_get,$page,20);
$total=$db->getNewsCountByTitle($keyword_get);
$total_page=ceil($total/20);
$page_str=page($page,$total_page,$keyword_get,"http://".$_SERVER["HTTP_HOST"]);
$site_title = $keyword_get . "-" . $site_name;
$keywords = "$keyword_get,$site_name";
$description = "关于{$keyword_get}的资讯文章-{$site_name}";


$new_news = $db->getNews(20);//获取最新新闻
$hot_news=$db->getHotNews();
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
$category_name = $keyword_get;
header("Referrer-Policy: no-referrer");
include "views/search.php";



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
            $result .="<a href=\"{$host}/s/{$category}-{$cpage}\"><span class=\"page\">{$cpage}</span></a>";
        }
        if ($distance > 0) {
            $result .="<a href=\"{$host}/s/{$category}-{$next_page}\"><span class=\"page\">››</span></a>";
        }
        return $result;
    } elseif ($page == $total_page) {
        $result .="<a href=\"{$host}/s/{$category}-{$pre_page}\"><span class=\"page\">‹‹</span></a>";
        $start = $total_page - 3 > 0 ? $total_page - 3 : $total_page - 1;
        for ($i = $start; $i <= $total_page; $i++) {
            if ($i == $page) {
                $result .= "<span class=\"page now-page\">{$page}</span>";
            } else {
                if ($i<=$total_page){
                    $result .="<a href=\"{$host}/ss/{$category}-{$i}\"><span class=\"page\">{$i}</span></a>";
                }
            }
        }
        return $result;

    } else {
        $result .="<a href=\"{$host}/s/{$category}-{$pre_page}\"><span class=\"page\">‹‹</span></a>";
        $start = $page - 2 > 0 ? $page - 2 : $page - 1;
        for ($i = $start; $i < $start +5; $i++) {
            if ($i == $page) {
                $result .= "<span class=\"page now-page\">{$page}</span>";
            } else {
                if ($i<=$total_page){
                    $result .="<a href=\"{$host}/s/{$category}-{$i}\"><span class=\"page\">{$i}</span></a>";
                }
            }
        }
        $result .="<a href=\"{$host}/s/{$category}-{$next_page}\"><span class=\"page\">››</span></a>";
        return $result;
    }
}

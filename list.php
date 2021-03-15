<?php
/**
 * Created by PhpStorm.
 * User: Dylan.L
 * Date: 2019/5/26
 * Time: 14:51
 */

$CONFIG=include 'config.php';
include 'DB.php';

$site_name=$CONFIG['site_name'];
$site_url=$CONFIG['site_url'];
$categorys=$CONFIG['categorys'];//导航
$db=new DB($CONFIG['db']);
$category_path=$_GET['category'];
$category_name=$categorys[$category_path];


$page=(int)$_GET['page'];
$per_page=20;
$host=$_SERVER['HTTP_HOST'];

$news_list=$db->getNewsByCategory($category_path,$page,$per_page);
$total_count=$db->getCategoryCount($category_path);
$total_page=ceil($total_count/20);
$page_str=page($page,$total_page,$category_path,"http://$host");
$site_title=$category_name."-".$site_name;
$keywords="$category_name,$site_name";
$description="{$category_name}栏目是{$site_name}下的一个重要栏目，{$category_name}栏目提供非常多的优质的，最新的资讯内容。";

$hot_news=$db->getHotNews($category_path);//获取最新新闻
$new_news=$db->getNews(20);
$tags=array();//标签
foreach ($news_list as $k=>$v){
    $tagarr=explode(",",$v['dkeys']);
    if (is_array($tagarr)){
        foreach ($tagarr as $item){
            if (isset($tags[$item])){
                $tags[$item]+=1;
            }else{
                $tags[$item]=1;
            }
        }
    }
}
arsort($tags);
$tags=array_slice($tags,0,30);
$tags=array_keys($tags);//最终标签
$js=isset($CONFIG['js'])?$CONFIG['js']:"";
header("Referrer-Policy: no-referrer");
include "views/list.php";


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
            $result .="<a href=\"{$host}/category/{$category}-{$cpage}.html\"><span class=\"page\">{$cpage}</span></a>";
        }
        if ($distance > 0) {
            $result .="<a href=\"{$host}/category/{$category}-{$next_page}.html\"><span class=\"page\">››</span></a>";
        }
        return $result;
    } elseif ($page == $total_page) {
        $result .="<a href=\"{$host}/category/{$category}-{$pre_page}.html\"><span class=\"page\">‹‹</span></a>";
        $start = $total_page - 3 > 0 ? $total_page - 3 : $total_page - 1;
        for ($i = $start; $i <= $total_page; $i++) {
            if ($i == $page) {
                $result .= "<span class=\"page now-page\">{$page}</span>";
            } else {
                if ($i<=$total_page){
                    $result .="<a href=\"{$host}/category/{$category}-{$i}.html\"><span class=\"page\">{$i}</span></a>";
                }
            }
        }
        return $result;

    } else {
        $result .="<a href=\"{$host}/category/{$category}-{$pre_page}.html\"><span class=\"page\">‹‹</span></a>";
        $start = $page - 2 > 0 ? $page - 2 : $page - 1;
        for ($i = $start; $i < $start +5; $i++) {
            if ($i == $page) {
                $result .= "<span class=\"page now-page\">{$page}</span>";
            } else {
                if ($i<=$total_page){
                    $result .="<a href=\"{$host}/category/{$category}-{$i}.html\"><span class=\"page\">{$i}</span></a>";
                }
            }
        }
        $result .="<a href=\"{$host}/category/{$category}-{$next_page}.html\"><span class=\"page\">››</span></a>";
        return $result;
    }
}
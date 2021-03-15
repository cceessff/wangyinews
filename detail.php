<?php
/**
 * Created by PhpStorm.
 * User: Dylan.L
 * Date: 2019/5/26
 * Time: 9:36
 */
$news_id = $_GET['id'];


use Overtrue\Pinyin\Pinyin;
include "vendor/autoload.php";
$CONFIG = include "config.php";
include "DB.php";
include_once 'phpanalysis/phpanalysis.class.php';
$db=new DB($CONFIG['db']);
$db->updateViewCount($news_id);
if (file_exists("cache/{$news_id}.html")){
    header("Referrer-Policy: no-referrer");
    $content=file_get_contents("cache/{$news_id}.html");
    echo $content;
    exit;
}

$site_name = $CONFIG['site_name'];
$site_url = $CONFIG['site_url'];
$db = new DB($CONFIG['db']);
$news_id = $_GET['id'];
$news_id=(int)$news_id;

$news = $db->getNewsById($news_id);
$category_path = $news['category_path'];
$category_name = $news['category_name'];

$keywords = $news['dkeys'];
$description = $news['digst'];
$news['title']=newsTitleWYC($news['title']);
$site_title = $news['title'] . "_" . $site_name;
$categorys = $CONFIG['categorys'];//导航

$news_content=genPinyin($news['content'],$_SERVER['HTTP_HOST']);

$relate_news = array();
$relate_tags=explode(",",$news['dkeys']);
if (count($relate_tags) > 0) {

    $relate_news=$db->getRelateNews($relate_tags);
}
$hot_news = $db->getHotNews($category_path);//获取最新新闻
$new_news=$db->getNews(20);

$tags = array();//标签
foreach ($relate_news as $k => $v) {
    $tagarr=explode(",",$v['dkeys']);
    if (is_array($tagarr)) {

        foreach ($tagarr as $item) {
            if (isset($tags[$item])) {
                $tags[$item] += 1;
            } else {
                $tags[$item] = 1;
            }
        }

    }
}
foreach ($hot_news as $k => $v) {
    $tagarr=explode(",",$v['dkeys']);
    if (is_array($tagarr)) {
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
$tags = array_slice($tags, 0, 50);
$tags = array_keys($tags);//最终标签
$js=isset($CONFIG['js'])?$CONFIG['js']:"";
header("Referrer-Policy: no-referrer");
ob_start();

include "views/detail.php";
$content=ob_get_clean();
if (!is_dir("cache")){
    mkdir("cache",0777,true);
}
file_put_contents("cache/{$news_id}.html",$content);
echo $content;



//120044205
//99920305
//https://m.tv.sohu.com/phone_playinfo?vid=118745165&site=2&appid=tv&api_key=f351515304020cad28c92f70f002261c&plat=17&sver=1.0&partner=1

function genPinyin($content,$host){
    $stripcontent=strip_tags($content);
    PhpAnalysis::$loadInit = false;
    $pa = new PhpAnalysis('utf-8', 'utf-8', false);
    $pa->LoadDict();
    $pa->SetSource($stripcontent);
    $pa->differMax = false;
    $pa->unitWord = true;
    $pa->StartAnalysis(true);
    $keywords = $pa->GetFinallyKeywords(15);
    $keywords=explode(",",$keywords);
    $pinyin_service=new Pinyin();
    foreach ($keywords as $keyword){

        $pinyin=$pinyin_service->sentence($keyword,PINYIN_TONE);
        $encode_url=urlencode($keyword);
        $replace="<a href=\"http://{$host}/s/{$encode_url}\" target='_blank'><ruby>{$keyword}<rp>[</rp><rt>{$pinyin}</rt><rp>]</rp></ruby></a>";

        $content=str_replace_once($keyword,$replace,$content);
    }
    return $content;

}


/**
 *字符串只替换一次函数
 **/
function str_replace_once($needle, $replace, $haystack) {
    $pos = strpos($haystack, $needle);
    if ($pos === false) {
        return $haystack;
    }
    return substr_replace($haystack, $replace, $pos, strlen($needle));
}
/**
 *标题伪原创函数
 **/
function newsTitleWYC($news_title) {
    //中文标点
    $char = "。、！？：；﹑•＂…‘’“”〝〞∕¦‖—　〈〉﹞﹝「」‹›〖〗】【»«』『〕〔》《﹐¸﹕︰﹔！¡？¿﹖﹌﹏﹋＇´ˊˋ―﹫︳︴¯＿￣﹢﹦﹤‐­˜﹟﹩﹠﹪﹡﹨﹍﹉﹎﹊ˇ︵︶︷︸︹︿﹀︺︽︾ˉ﹁﹂﹃﹄︻︼（）";

    $pattern = array(
        "/[[:punct:]]/i", //英文标点符号
        '/[' . $char . ']/u', //中文标点符号
        '/[ ]{2,}/',
    );
    $news_title = preg_replace($pattern, '', $news_title);

    PhpAnalysis::$loadInit = false;
    $pa = new PhpAnalysis('utf-8', 'utf-8', false);
    $pa->LoadDict();
    $pa->SetSource($news_title);
    $pa->differMax = true;
    $pa->unitWord = true;
    $pa->StartAnalysis(true);
    $result = $pa->GetSimpleResult();
    shuffle($result);
    $news_title = implode('', $result);
    return $news_title;

}

<?php

$ADMIN_CONFIG = include 'config.php';
$ip = getIP();
if (!in_array($ip, $ADMIN_CONFIG['allow_ips'])) {
    http_response_code(403);
    exit;

}

$action = isset($_GET['action']) ? $_GET['action'] : 'index';
switch ($action) {
    case 'index':
        include "views/index.php";
        break;
    case 'news_list':
        $news_list = getNewsList($ADMIN_CONFIG['db']);
        echo json_encode($news_list, JSON_UNESCAPED_UNICODE);
        break;
    case 'del_news':
        $news_id = $_GET['news_id'];
        $result = delNews($news_id, $ADMIN_CONFIG['db']);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        break;
    case 'edit_news':
        $news_id = $_GET['news_id'];
        include 'DB.php';
        $db = new DB($ADMIN_CONFIG['db']);
        $news = $db->getNewsById($news_id);
        include 'views/edit_news.php';
        break;
    case "update_news":
        updateNews($ADMIN_CONFIG['db']);
        break;
    case "publish_news":
        if (strtoupper($_SERVER['REQUEST_METHOD'])==='GET'){
            include 'views/publish_news.php';
        }elseif (strtoupper($_SERVER['REQUEST_METHOD'])==='POST'){
            publishNews($ADMIN_CONFIG['db'],$ADMIN_CONFIG['categorys']);
        }

        break;
    case "upload_img":
        uploadImg();
        break;


}


function getNewsList($db_config)
{
    include 'DB.php';
    $db = new DB($db_config);
    $page = $_GET['page'];
    $page = intval($page) > 0 ? intval($page) : 1;
    $data = $db->getNewsList($page, 20, 'ptime desc');
    $count = $db->getCount();
    return array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data);


}

function delNews($news_id, $db_config)
{
    include 'DB.php';
    $db = new DB($db_config);
    $result = $db->delNewsById(intval($news_id));
    if ($result) {
        return array('code' => '0');
    }
    return array('code' => 1);
}

function updateNews($db_config)
{
    if (!$id = $_POST['id']) {
        echo json_encode(array('code' => 2, 'msg' => '非法请求'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    if (!$title = $_POST['title']) {
        echo json_encode(array('code' => 3, 'msg' => '标题不能为空'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    if (!$dkeys = $_POST['dkeys']) {
        echo json_encode(array('code' => 4, 'msg' => '关键词不能为空'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    if (!$digst = $_POST['digst']) {
        echo json_encode(array('code' => 5, 'msg' => '简介不能为空'), JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (!$content = $_POST['content']) {
        echo json_encode(array('code' => 6, 'msg' => '内容不能为空'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    include 'DB.php';
    $db = new DB($db_config);
    if (isset($_POST['ptime']) && $ptime = strtotime($_POST['ptime'])) {
        $ptime = time();
    }
    $data = array('id' => $id, 'ptime' => $ptime, 'digst' => $digst, 'dkeys' => $dkeys, 'title' => $title, 'content' => $content);
    $result = $db->updateNews($data);
    if ($result) {
        echo json_encode(array('code' => 0, 'msg' => '更新成功'));
    } else {
        echo json_encode(array('code' => 1, 'msg' => '更新失败'));
    }

}

function uploadImg()
{
    $type=$_GET['type'];
    $thumb = $_FILES[$type];
    switch ($thumb['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            echo json_encode(array('code' => 1, 'msg' => '没有文件被上传'), JSON_UNESCAPED_UNICODE);
            exit;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            echo json_encode(array('code' => 2, 'msg' => '文件超过限制'), JSON_UNESCAPED_UNICODE);
            exit;
        default:
            echo json_encode(array('code' => 3, 'msg' => '未知错误'), JSON_UNESCAPED_UNICODE);
            exit;
    }
    if ($thumb['size'] > 100*1024) {
        echo json_encode(array('code' => 2, 'msg' => '文件超过限制'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search($finfo->file($thumb['tmp_name']), array('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif',), true)) {
        echo json_encode(array('code' => 4, 'msg' => '文件类型不正确'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    $filename=sha1($thumb['tmp_name'].time());
    $date=date('Ymd');
    if(!is_dir("../uploads/{$type}/{$date}")){
        mkdir("../uploads/{$type}/{$date}",0777,true);
    }
    if (!move_uploaded_file($thumb['tmp_name'], sprintf("../uploads/{$type}/{$date}/%s.%s", $filename, $ext))) {
        echo json_encode(array('code' => 4, 'msg' => '文件上传失败'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    echo json_encode(array('errno'=>0,'code' => 0, 'msg' => '文件上传成功','data'=>array(sprintf("/uploads/{$type}/{$date}/%s.%s", $filename, $ext)),"src"=>sprintf("/uploads/{$type}/{$date}/%s.%s", $filename, $ext)), JSON_UNESCAPED_UNICODE);
}

function publishNews($db_config,$category_config){
    if (!$img = $_POST['img']) {
        echo json_encode(array('code' => 2, 'msg' => '封面图片不能为空'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    if (!$category = $_POST['category']) {
        echo json_encode(array('code' => 7, 'msg' => '新闻类型不能为空'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    if (!$title = $_POST['title']) {
        echo json_encode(array('code' => 3, 'msg' => '标题不能为空'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    if (!$dkeys = $_POST['dkeys']) {
        echo json_encode(array('code' => 4, 'msg' => '关键词不能为空'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    if (!$digst = $_POST['digst']) {
        echo json_encode(array('code' => 5, 'msg' => '简介不能为空'), JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (!$content = $_POST['content']) {
        echo json_encode(array('code' => 6, 'msg' => '内容不能为空'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    include 'DB.php';
    $db = new DB($db_config);
    if (isset($_POST['ptime']) && $ptime = strtotime($_POST['ptime'])) {
        $ptime = time();
    }
    $category_name=$category_config[$category];
    $data = array('img' => $img, 'category_path'=>$category,'category_name'=>$category_name,'ptime' => $ptime, 'digst' => $digst, 'dkeys' => $dkeys, 'title' => $title, 'content' => $content);
    $result = $db->insertNews($data);
    if ($result) {
        echo json_encode(array('code' => 0, 'msg' => '添加成功'));
    } else {
        echo json_encode(array('code' => 1, 'msg' => '添加失败'));
    }

}


function getIP() /*获取客户端IP*/
{
    if (@$_SERVER["HTTP_X_FORWARDED_FOR"])
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if (@$_SERVER["HTTP_CLIENT_IP"])
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    else if (@$_SERVER["REMOTE_ADDR"])
        $ip = $_SERVER["REMOTE_ADDR"];
    else if (@getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (@getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (@getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else
        $ip = "Unknown";
    return $ip;

}
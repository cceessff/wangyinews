<?php
/**
 * Created by PhpStorm.
 * User: Dylan.L
 * Date: 2020/2/28
 * Time: 13:04
 */

class DB
{
    public function __construct($config)
    {
        $dbms = 'mysql'; //数据库类型
        $host = $config['host']; //数据库主机名
        $dbName = $config['database']; //使用的数据库
        $user = $config['username']; //数据库连接用户名
        $pass = $config['password']; //对应的密码
        $dsn = "$dbms:host=$host;dbname=$dbName";
        try {
            $this->conn = new PDO($dsn, $user, $pass); //初始化一个PDO对象
        } catch (Exception $e) {
            print_r($e);
        }
    }

    public function getNewsList($page, $limit, $sort)
    {
        if (!is_numeric($limit) || !is_numeric($page)) {
            return null;
        }
        $start = ($page - 1) * $limit;
        $stat = $this->conn->prepare("select id,category_name,ptime,dkeys,title,digst from wangyinews order by ? desc limit ?,?");
        $stat->bindParam(1, $sort, PDO::PARAM_STR);
        $stat->bindParam(2, $start, PDO::PARAM_INT);
        $stat->bindParam(3, $limit, PDO::PARAM_INT);
        $stat->execute();
        $result = $stat->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getCount($where = '')
    {
        if (!$where) {
            $stat = $this->conn->prepare("select count(*) as count from wangyinews");
        } else {
            $stat = $this->conn->prepare("select count(*) as count from wangyinews where ?");
            $stat->bindParam(1, $where, PDO::PARAM_STR);
        }
        $stat->execute();
        $result = $stat->fetch();
        return $result ? $result['count'] : 0;

    }

    public function delNewsById($id)
    {
        if (!$id || !is_numeric($id)) {
            return false;
        }
        $stat = $this->conn->prepare("delete from wangyinews where id=?");
        $stat->bindParam(1, $id);
        $stat->execute();
        if ($stat->rowCount() === 0) {
            return false;
        }
        return true;

    }

    public function getNewsById($id)
    {
        if (!$id || !is_numeric($id)) {
            return false;
        }
        $stat = $this->conn->prepare("select id,title,dkeys,digst,content,ptime from wangyinews where id=? limit 1");
        $stat->bindParam(1, $id);
        $stat->execute();
        $news=$stat->fetch(PDO::FETCH_ASSOC);
        return $news;
    }

    public function updateNews($data)
    {
        if (!is_array($data)||empty($data)){
            return false;
        }
        $stat=$this->conn->prepare("update wangyinews set title=?,ptime=?,dkeys=?,digst=?,content=? where id=?");
        $stat->bindParam(1,$data['title'],PDO::PARAM_STR);
        $stat->bindParam(2,$data['ptime'],PDO::PARAM_INT);
        $stat->bindParam(3,$data['dkeys'],PDO::PARAM_STR);
        $stat->bindParam(4,$data['digst'],PDO::PARAM_STR);
        $stat->bindParam(5,$data['content'],PDO::PARAM_STR);
        $stat->bindParam(6,$data['id'],PDO::PARAM_INT);
        $stat->execute();
        if ($stat->rowCount()){
            return true;
        }
        return false;
    }

    public function insertNews($data)
    {
        if (!is_array($data)||empty($data)){
            return false;
        }
        $stat=$this->conn->prepare("insert into wangyinews(title,digst,dkeys,ptime,content,image,category_path,category_name,docid)values (?,?,?,?,?,?,?,?,?)");
        $stat->bindParam(1,$data['title'],PDO::PARAM_STR);
        $stat->bindParam(2,$data['digst'],PDO::PARAM_STR);
        $stat->bindParam(3,$data['dkeys'],PDO::PARAM_STR);
        $stat->bindParam(4,$data['ptime'],PDO::PARAM_INT);
        $stat->bindParam(5,$data['content'],PDO::PARAM_STR);
        $stat->bindParam(6,$data['img'],PDO::PARAM_STR);
        $stat->bindParam(7,$data['category_path'],PDO::PARAM_STR);
        $stat->bindParam(8,$data['category_name'],PDO::PARAM_STR);
        $docid=strtoupper(substr(md5($data['title'].$data['ptime']),8,16));
        $stat->bindParam(9,$docid,PDO::PARAM_STR);
        $result= $stat->execute();
        if (!$result){
            error_log(json_encode($stat->errorInfo(),JSON_UNESCAPED_UNICODE)."\n",3,'test.log');
        }
        if ($stat->rowCount()){
            return true;
        }
        return false;

    }
}
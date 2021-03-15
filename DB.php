<?php

class DB
{
    public $conn;

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

    /**获取最新的新闻
     * @param array $category 新闻种类，数组
     * @param $limit
     * @param $offset
     * @return array 返回查询结果
     */
    public function getNews($count)
    {

        if ( !is_numeric($count)) {
            return null;
        }
        $datetime=new DateTime();
        $datetime->sub(new DateInterval("P1D"));
        $time=$datetime->getTimestamp();
        $stat = $this->conn->prepare("select id,category_name,category_path,ptime,image,dkeys,title,digst,view_count from wangyinews where ptime>{$time}  order by ptime desc limit 0,?");
        $stat->bindParam(1, $count, PDO::PARAM_INT);
        $stat->execute();
        $result = $stat->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getLunbo()
    {
        $datetime=new DateTime();
        $datetime->sub(new DateInterval("P1D"));
        $time=$datetime->getTimestamp();
        $stat = $this->conn->prepare("select id,category_name,category_path,ptime,image,dkeys,title,digst,view_count from wangyinews where ptime>{$time}  order by comment_count desc limit 0,5");
        $stat->bindParam(1, $count, PDO::PARAM_INT);
        $stat->execute();
        $result = $stat->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function getNewsById( $id)
    {
        if (!is_numeric($id)) {
            return null;
        }
        $sql = "select category_name,category_path,ptime,dkeys,title,digst,content,view_count,comment_count from wangyinews where id=?";
        $stat=$this->conn->prepare($sql);
        $stat->bindParam(1,$id,PDO::PARAM_INT);
        $stat->execute();
        $result=$stat->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getHotNews($category='')
    {
        //$category=$this->conn->quote($category,PDO::PARAM_STR);
        if ($category){
            $sql="select a.id,title,dkeys,category_path,category_name from wangyinews as a  right join (select id from wangyinews where category_path=? ORDER BY comment_count DESC limit 0,20) as b on b.id=wangyinews.id";
            //$sql="SELECT id,title,dkeys,category_path,category_name from wangyinews where category_path=? ORDER BY comment_count DESC limit 0,20";
        }else{
            $datetime=new DateTime();
            $datetime->sub(new DateInterval("P1D"));
            $time=$datetime->getTimestamp();
            $sql="SELECT id,title,dkeys,category_path,category_name from wangyinews where ptime>{$time}  ORDER BY comment_count DESC limit 0,20";
        }
        $stat=$this->conn->prepare($sql);
        $stat->bindParam(1,$category,PDO::PARAM_STR);
        $stat->execute();
        $result=$stat->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }

    public function getRelateNews(array  $tags)
    {
        $tags=implode(" ",$tags);
        $sql="select id,category_path,category_name,ptime,image,dkeys,title,digst from wangyinews where  match (dkeys) against (\"{$tags}\") limit 0,20";
        $stat=$this->conn->prepare($sql);
        $stat->execute();
        $result=$stat->fetchAll();

        return $result;

    }

    public function getNewsByCategory($category,$page,$limit)
    {
        $page=($page-1)*$limit;
        $sql = "select id,category_name,category_path,ptime,dkeys,title,dkeys,digst,view_count,image from wangyinews where category_path=? limit ?,?";
        $stat=$this->conn->prepare($sql);
        $stat->bindParam(1,$category,PDO::PARAM_STR);
        $stat->bindParam(2,$page,PDO::PARAM_INT);
        $stat->bindParam(3,$limit,PDO::PARAM_INT);
        $stat->execute();
        $result=$stat->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    }
    public function getCategoryCount($category){
        $sql="select count(id) as count from wangyinews where category_path=?";
        $stat=$this->conn->prepare($sql);
        $stat->bindParam(1,$category,PDO::PARAM_STR);
        $stat->execute();
        $result=$stat->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function getNewsByTag($tag,$page,$limit)
    {
        $offset=($page-1)*$limit;
        $sql="select id,category_name,category_path,title,dkeys,ptime,digst,image,view_count from wangyinews where match (dkeys) against (?) limit {$offset},{$limit}";
        $stat=$this->conn->prepare($sql);
        $stat->bindParam(1,$tag,PDO::PARAM_STR);
        $stat->execute();
        $result=$stat->fetchAll();
        return $result;

    }
    public function getNewsCountByTag($tag)
    {
        $sql="select count(id) as count from wangyinews where match (dkeys) against (?)";
        $stat=$this->conn->prepare($sql);
        $stat->bindParam(1,$tag,PDO::PARAM_STR);
        $stat->execute();
        $result=$stat->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];

    }
    public function getNewsByTitle($title,$page,$limit)
    {
        $offset=($page-1)*$limit;
        $sql="select id,category_name,category_path,title,dkeys,ptime,digst,image,view_count from wangyinews where match (title) against (?) limit {$offset},{$limit}";
        $stat=$this->conn->prepare($sql);
        $stat->bindParam(1,$title,PDO::PARAM_STR);
        $stat->execute();
        $result=$stat->fetchAll();
        return $result;

    }
    public function getNewsCountByTitle($title)
    {
        $sql="select count(id) as count from wangyinews where match (title) against (?)";
        $stat=$this->conn->prepare($sql);
        $stat->bindParam(1,$title,PDO::PARAM_STR);
        $stat->execute();
        $result=$stat->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];

    }

    public function updateViewCount($id)
    {   $id=intval($id);
        $sql="update wangyinews set view_count=view_count+1 where id=$id";
        $this->conn->exec($sql);
    }

}

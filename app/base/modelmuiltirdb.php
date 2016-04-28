<?php
require_once(dirname(__FILE__)."/querybuilder.php");

class ModelMultiRdb{
  public $conn;
  public $table;
  public $info;
  public $loader;
  public $sys;
  protected $__config = null;
  protected $__connected = false;
  public function __config($config = null) {
    if (! empty ( $config )) {
      $this->__config = $config;
    }
  }

  public function isActive(){
      return $this->__connected;
  }

  public function init($config = null) {
		global $_connections;
		if (empty ( $config ))
			return;
		$dsn = "mysql:host=" . $config ['host'] . ";dbname=" . $config ['database'];
		if (! empty ( $_connections [$dsn] )) {
			$this->conn = $_connections [$dsn];
      $this->__connected =true;
		} else {
			try {
				$this->conn = new PDO ( $dsn, $config ['user'], $config ['password'] );
				$_connections [$dsn] = $this->conn;
        $this->__connected =true;
			} catch ( PDOException $e ) {
				echo $e->getMessage ();
				die ();
			}
		}
		$this->conn->exec ( "SET NAMES 'UTF8'" );
	}

  public function find ( $fields,
                          $cond=null,
                          $param=null,
                          $group=null,
                          $order=null,
                          $limit=null ){
      $qb = new QueryBuilder();
      $qb = $qb->select($fields)->where($cond)->groupby($group)->orderby($order);
      if(empty($limit)){
        $limit = "0,10";
      }
      $qb = $qb->limit($limit);
      return $this->doQuery($qb);
  }

  public function findOne ($fields,
                          $cond,
                          $param=null,
                          $group=null,
                          $order=null){
      $data = $this->find($fields,
                          $cond,
                          $param=null,
                          $group=null,
                          $order=null,"0,1");
      if(empty($data))
        return false;
      return $data[0];
  }

  protected function doQuery(&$qb,$param=null){
    if(!$this->isActive()){
      exit(json_encode(array("success"=>false,"msg"=>"数据库连接未激活")));
    }
    return $qb->query($param);
  }







}
?>

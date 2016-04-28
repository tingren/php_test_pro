<?php
class ModelMultiRedis extends Redis {

	public function __construct($config=null){
		
	}

	public function __config($config){
		$this->__config = $config;
	}

	public function init($config=null){
		global $_connections;
		$config = empty($config)?$this->__config:$config;
		if(!empty($config)){
			$this->__connect($config);
		}
	}


	protected function __connect($config){
		$host = $config['host'];
		parent::__construct();
		if($this->__connected){
			if($config['host']==$this->__config['host']  && $config['database']==$this->__config['database']){
				return false;
			}
		}
		$this->connect($host);
		$this->__connected = true;
		/*
		if($this->__connected){
			if($config['host']==$this->__config['host']  && $config['database']==$this->__config['database']){
				return false;
			}
		}		
		global $_connections;
		$dsn = "mongodb://${config['host']}";
		if(!empty($_connections[$dsn])){
			$this->client = $_connections[$dsn];
		}else{
			$this->client = new MongoClient($dsn,array('connectTimeoutMS'=>99999999));
			$_connections[$dsn] = $this->client;
		}
			
		$dbname = $config['database'];
		$this->database = $this->client->selectDB($dbname);
		if($config['auth']){
			$rs = $this->database->authenticate($config['user'] && $config['password']);
			if(empty($rs['ok'])){
				die("数据库验证错误");
			}
		}
		parent:: __construct($this->database,$this->table);
		if(null != $this->client){
			$this->__connected = true;
		}*/
	}
}


?>
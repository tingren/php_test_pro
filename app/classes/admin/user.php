<?php

namespace admin;

class User extends \FrontBase {
	
	/**
	 * 
	 */
	public function login() {
		//-------------------参数接受开始--------------------------//
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];
		//-------------------参数接受开始--------------------------//
		$mAdmin = new \model\Admin($this->config("database/yun"));
		$cond = array();
		$cond['username'] = $username;
		$cond['password'] = $password;
		$cond['level'] = array('$gt'=>0);
		
		if($mAdmin->count($cond)==0){
			$this->exitWithJson(false,"用户名或密码错误");
		}

		$data = $mAdmin->findOne($cond);
		$_SESSION['uid'] = $data["_id"];
		$_SESSION['level'] = $data['level'];
		$this->exitWithJson(true,"用户验证成功");
	
	}
	
	
	public function disable(){
		//-------------------参数接受开始--------------------------//
		$uid = $_REQUEST['id'];
		//-------------------参数接受开始--------------------------//
		if(empty($uid)){
			$this->exitWithJson(false,"没有指定UID");	
		}
		$cond = array();
		$data = array();
		$cond['_id'] = new \MongoId($uid);
		$data['level'] = 0;
		$mAdmin = new \model\Admin($this->config("database/yun"));
		$mAdmin->update($cond,array('$set'=>$data));
		$this->exitWithJson(true,"用户已经禁用");
	}
	
	/**
	 * 
	 */
	public function add() {
		// ----------------------参数接收开始-------------------------//
		$username = $_REQUEST ['username'];
		$password = $_REQUEST ['password'];
		$realname = $_REQUEST['realname'];
		$level = $_REQUEST['level'];
		// ----------------------参数接受结束-------------------------//
		$mAdmin = new \model\Admin($this->config("database/yun"));
		$cond = array();
		$data = array();
		$cond['username']=$username;
		
		$data['username']=$username;
		$data['realname']=$realname;
		$data['password']=md5($password);
		$data['level'] = new \MongoInt64($level);
		
		//-----------------------------------------------------------//
		if(empty($cond['username'])){
			$this->exitWithJson(false,"未填写用户名");
		}
		
		if($mAdmin->count($cond)>0){
			$this->exitWithJson(false,"账户已存在");
		}
		
		//----------------------------------------------------------//
		$mAdmin->save($data);
		if(empty($data["_id"])){
			$this->exitWithJson(true,"账户",array("user"=>$data));
		}
		$this->exitWithJson(false,"账户创建异常");
	}
	
}
?>
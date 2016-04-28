<?php

namespace admin;

class Notification extends \FrontBase {
	public function __construct() {
	}
	public function add() {
		// ---------------------参数接收开始-----------------------//
		$title = $_REQUEST ['title'];
		$content = $_REQUEST ['content'];
		$id = $_REQUEST ['id'];
		// ---------------------参数接收结束-----------------------//
		if (empty ( $title )) {
			$this->exitWithJson ( false, "标题没有设定" );
		}
		
		if (empty ( $content )) {
			$this->exitWithJson ( false, "内容未设定" );
		}
		
		$mNotification = new \model\Notification ( $this->config ( "database/yun" ) );
		$data = array ();
		$data ['title'] = $title;
		$data ['content'] = $content;
		$data ['last_update'] = new \MongoDate ( strtotime ( "now" ) );
		$data ['status'] = 1;
		if (empty ( $id )) {
			$data ['_id'] = new \MongoId ( $id );
		}
		$mNotification->save ( $data );
		$this->exitWithJson ( true, "公告已经发布", array (
				"content" => $data 
		) );
	}
	public function disable() {
		// ---------------------参数接收开始----------------------//
		$id = $_REQUEST ['id'];
		// ---------------------参数接收结束----------------------//
		if (empty ( $id )) {
			$this->exitWithJson ( false, "没有指定id" );
		}
		$mNotification = new \model\Notification ( $this->config ( "database/yun" ) );
		$cond = array ();
		$data = array ();
		
		$cond ['_id'] = new \MongoId ( $id );
		$data ['status'] = 0;
		
		$mNotification->update ( $cond, array (
				'$set' => $data 
		) );
	}
	
	
	public function getNotification() {
		//----------------------获取参数开始-----------------------//
		$status = $_REQUEST['status'];
		//----------------------获取参数结束-----------------------//
		$cond = array();
		if(!empty($status)){
			$cond['status']=new \MongoInt64($status);
		}
		$mNotification = new \model\Notification();
		$cursor = $mNotification->find()->sort(array("last_update"=>-1));
		$data = iterator_to_array($cursor);
		$this->exitWithJson(true,"数据已经读取",array("data"=>$data));
	}
}
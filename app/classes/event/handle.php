<?php
namespace event;
use tool\Debug as DBG;
class Handle extends \FrontBase{
	protected $result;
	protected $extra;
	public $from = array (
			"1" => "weixinid",
			"2" => "qqid",
			"3" => "sinaid",
			"4" => "mobile"
	);
	public function setResult($rs){
		$this->result = $rs;
		$this->setExtra($rs);
	}

	public function setExtra($extra){
		$this->extra = $extra;
		if(empty($this->extra) || $this->extra['success']==false){
			//DBG::log(array("action"=>"__exit_extra"));
			exit();
		}
	}


	/**
	 * 普通登陆后后置操作
	 */
	public function __after_signup(){
		$this->do_after_do_sign();
	}

	/**
	 * 使用手机登陆后
	 */
	public function __after_signupByMobile(){
		$this->do_after_do_sign();
	}

	/**
	 * 签到
	 */
	public function do_after_do_sign(){
		if($this->extra['success']==true){
			//$uid = $this->extra["uid"];
			$tuid = $_POST['uid'];
			$from = $_POST['from'];
			if(empty($this->from[$from]))
				return;
			$key = $this->from[$from];
			$cond[$key] = $tuid;
			$mUser = new \model\UserModel();
			$data = $mUser->findOne($cond);
			if(empty($data))
				return;
			$uid = $data["_id"]->__toString();
			$mLog = new \model\Eventlog();
			$mLog->log($uid,$mLog->action['sign']);
			/**
			 * 经验值判断
			 */
			$cond['uid']=$uid;
			$cond['action']=$mLog->action['signup'];
			$cond['updated_date']['$gte'] = new \MongoDate(strtotime(Date("Y-m-d"." 00:00:00")));
			$cond['updated_date']['$lte'] = new \MongoDate(strtotime(Date("Y-m-d"." 23:59:59")));
			if($mLog->count($cond)<1){
				//首次登录，加分
				$mUser = new \model\UserModel();
				$mUser->setScore($uid,10);
			}
		}
	}
	/**
	 * 用户发帖后
	 */
	public function __after_save_message(){
		if($this->extra['success']==true){
			$uid = $_POST['uid'];
			$mLog = new \model\Eventlog();
			$mLog->log($uid,$mLog->action['publish']);
			/**
			 * 经验值判断
			*/
			$cond['uid']=$uid;
			$cond['action']=$mLog->action['publish'];
			$cond['updated_date']['$gte'] = new \MongoDate(strtotime(Date("Y-m-d"." 00:00:00")));
			$cond['updated_date']['$lte'] = new \MongoDate(strtotime(Date("Y-m-d"." 23:59:59")));
			if($mLog->count($cond)<2){
				//首次登录，加分
				$mUser = new \model\UserModel();
				$mUser->setScore($uid,10);
			}
		}
	}
	public function __after_save_reply(){
		if($this->extra['success']==true){
			//$uid = $this->extra["uid"];
			$uid = $_POST["uid"];
			$mLog = new \model\Eventlog();
			$mLog->log($uid,$mLog->action['comment']);
			/**
			 * 经验值判断
			*/
			$cond['uid']=$uid;
			$cond['action']=$mLog->action['comment'];
			$cond['updated_date']['$gte'] = new \MongoDate(strtotime(Date("Y-m-d"." 00:00:00")));
			$cond['updated_date']['$lte'] = new \MongoDate(strtotime(Date("Y-m-d"." 23:59:59")));
			if($mLog->count($cond)<10/2){//每次评论积分为5，一天最多30
				//首次登录，加分
				$mUser = new \model\UserModel();
				$mUser->setScore($uid,2);
			}
			$message_id=$_POST["message_id"];
			$message_model = new \model\Message ();
			$message = $message_model->findOne ( array (
					"_id" => new \MongoId ($message_id)
			) );
			$to_user_id= $message ['user_id'];
			$mLog->log($to_user_id,$mLog->action['tnemmoc']);
			$cond['uid']=$to_user_id;
			$cond['action']=$mLog->action['tnemmoc'];
			if($mLog->count($cond)<150/5){//每次被评积分为5，一天最多120
				//首次登录，加分
				$mUser = new \model\UserModel();
				$mUser->setScore($to_user_id,5);
			}
			$this->notify($uid,$to_user_id,"reply");
			if(!empty($_POST['reply_to_uid'])){
				$this->notify($uid,$to_user_id,"reply");
			}


		}
	}
	public function __after_zan(){
		if($this->extra['success']==true && $this->extra['type']==1){
			//$uid = $this->extra["uid"];
			$uid = $_POST["uid"];
			$mLog = new \model\Eventlog();
			$mLog->log($uid,$mLog->action['zan']);
			/**
			 * 经验值判断
			*/
			$cond['uid']=$uid;
			$cond['action']=$mLog->action['zan'];
			$cond['updated_date']['$gte'] = new \MongoDate(strtotime(Date("Y-m-d"." 00:00:00")));
			$cond['updated_date']['$lte'] = new \MongoDate(strtotime(Date("Y-m-d"." 23:59:59")));
			if($mLog->count($cond)<5){//每次评论积分为1，一天最多5
				//首次登录，加分
				$mUser = new \model\UserModel();
				$mUser->setScore($uid,1);
			}
			//$message_id=$this->extra["message_id"];
			$message_id = $_POST['message_id'];
			$message_model = new \model\Message ();
			$message = $message_model->findOne ( array (
					"_id" => new \MongoId ($message_id)
			) );
			$to_user_id= $message ['user_id'];
			$mLog->log($to_user_id,$mLog->action['naz']);
			$cond['uid']=$to_user_id;
			$cond['action']=$mLog->action['naz'];
			if($mLog->count($cond)<60/2){//每次被评积分为2，一天最多60
				//首次登录，加分
				$mUser = new \model\UserModel();
				$mUser->setScore($uid,2);
			}
			//DBG::log(array("action"=>"before_message"));
			//点赞信息推送
			$this->notify($uid,$to_user_id,"zan");
		}
	}

	public function __after_follow(){
		if($this->extra['success']==true&&"follow"==$_POST["operation"]){
				//把消息推送给被关注者
				$from = $_POST['uid'];
				$to = $_POST['to'];
				$type = "follow";
				$this->notify($from,$to,$type);
		}
	}

	protected function notify($from,$to,$type){
		if($from == $to){
			return;
		}


		$mUser = new \model\UserModel();
		$who = $mUser->getUserField($from,"nick");
		$device = $mUser->getDevice($to);
		if(empty($device))
			return false;
		$msg = $who;
		switch($type){
			case "zan":
				$msg .="赞了你";
				break;
			case "follow":
				$msg .= "关注了你";
				break;
			case "reply":
				$msg .="回复了你";
				break;
		}
		$config = $this->config("umeng/default");
		$umeng = $this->library("Umeng",$config);
		$mFeed = new \model\Feed();
		// $unread = $mFeed->countUnread($to);
		//DBG::log(array("event"=>"notify","to"=>$to,"from"=>$from,"type"=>$type));
		$unread = $mFeed->countUnread2($to,$type);
		$method = ($device['device_type']==1)?"sendAndroidUnicast":"sendIOSUnicast";
		//if($mUser->isActive($to))
		//	return;
		//echo $device['device_token'];
		$extra = array("k1_event"=>"notify","flag"=>"message","unread".$type=>$unread);
		//$extra = null;
		$umeng->$method($device['device_token'],$msg,$msg,$msg,$extra);
		//DBG::log(array("sendNotify"=>true,"msg"=>$msg));
	}

	
	
	public function __after_modifyNick(){
		if($this->extra['success']==true){
			$uid = $_POST['uid'];
			if(empty($uid))
				return false;
			$user = new \model\UserModel();
			$rs = $user->getUserInfo($uid);
			if(empty($rs))
				return;
			$result['uid']=$uid;
			$result['nick'] = $rs['nick'];
			$result['mobile'] = $rs['mobile'];
			$result['avatar'] = $rs['avatar'];
			$result['gender'] = $s['gender'];
			$imservice = new \api\Imservice();
			$imservice->sync($result);	
		}	
	}

}
?>

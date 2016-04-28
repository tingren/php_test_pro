<?php

namespace tool;
class Shell extends \FrontBase {
	public $level = array (
			array (
					"0" => 1,
					"100" => 2,
					"200" => 3,
					"400" => 4,
					"600" => 5,
					"800" => 6
			),
			array (
					"1000" => 7,
					"1500" => 8,
					"2500" => 9,
					"5000" => 10,
					"7500" => 11
			),
			array (
					"10000" => 12,
					"15000" => 13,
					"20000" => 14,
					"25000" => 15,
					"50000" => 16,
					"75000" => 17
			),
			array (
					"100000" => 18,
					"150000" => 19,
					"250000" => 20
			)
	);
	private function getLevel($score) {
		if (empty ( $score ))
			return 1;
		if ($score > 25000)
			return 20;

		$lenth = strlen ( $score );
		$group = 0;
		switch (lenth) {
			case 0 :
				$group = 0;
				break;
			case 1 :
				$group = 0;
				break;
			case 2 :
				$group = 0;
				break;
			case 3 :
				$group = 0;
				break;
			case 4 :
				$group = 1;
				break;
			case 5 :
				$group = 2;
				break;
			case 6 :
				$group = 3;
				break;
		}

		$tmp = $this->level [$group];

		foreach ( $tmp as $key => $val ) {
			if ($score < $key) {
				return $val - 1;
			}
		}
	}
	public function updateLevel() {
		$mUser = new \model\UserModel ();
		$cond ['score'] ['$exists'] = true;
		$total = $mUser->count ( $cond );
		$pagesize = 10000;
		if (empty ( $total ))
			return;
		$pages = ceil ( $total / $pagesize );
		for($i = 0; $i < $pages; ++ $i) {
			$skip = $i * $pagesize;
			$cursor = $mUser->find ( $cond )->skip ( $skip )->limit ( $limit );
			$cursor = $cursor->fields ( array (
					"_id" => true,
					"score" => true
			) );
			$data = iterator_to_array ( $cursor );
			if (empty ( $data )) {
				break;
			}
			foreach ( $data as $key => $value ) {
				$level = $this->getLevel ( $value ['score'] );
				$mUser->update ( array (
						"_id" => $value ["_id"]
				), array (
						'$set' => array (
								"level" => new \MongoInt64 ( $level )
						)
				) );
			}
		}
	}
	public function fixRel() {
		$mUser = new \model\UserModel ();
		$mRel = new \model\Relation();
		$cond = array();
		$total = $mUser->count ( $cond );
		$pagesize = 10000;
		if (empty ( $total ))
			return;
		$pages = ceil ( $total / $pagesize );
		for($i = 0; $i < $pages; ++ $i) {
			$skip = $i * $pagesize;
			$cursor = $mUser->find ( $cond )->skip ( $skip )->limit ( $limit );
			$cursor = $cursor->fields ( array (
					"_id" => true,
			) );
			$data = iterator_to_array ( $cursor );
			if (empty ( $data )) {
				break;
			}
			foreach ( $data as $key => $value ) {
				$level = $this->getLevel ( $value ['score'] );
				$mUser->update ( array (
						"_id" => $value ["_id"]
				), array (
						'$set' => array (
								"fans" => $mRel->numOfFans($value["_id"]->__toString()),
								"follow"=> $mRel->numOfFollow($value["_id"]->__toString())
						)
				) );
			}
		}
	}


	/**
	* 给所有的表加上索引
	*/
	public function makeIndex(){
			$model = new \model\UserModel();
			$model->deleteIndexes();
			$model->createIndex(array("sinaid"=>1),array('unique'=>true));
			$model->createIndex(array("qqid"=>1),array('unique'=>true));
			$model->createIndex(array("wexinid"=>1),array('unique'=>true));
			$model->createIndex(array("mobile"=>1),array('unique'=>true));
			$model->createIndex(array("nick"=>1));

			$model = null;
			$model = new \model\Message();
			$model->createIndex(array("is_rec"=>1));
			$model->createIndex(array("user_id"=>1));
			$model->createIndex(array("creation_date"=>1));
			$model->createIndex(array("tags"=>1));

			$model = null;
			$model = new \model\Msq();
			$model->createIndex(array("user_id"=>1));
			$model->createIndex(array("to_user_id"=>1));
			$model->createIndex(array("type"=>1));
			$model->createIndex(array("no_read"=>1));
			$model->createIndex(array("creation_date"=>-1));
	}
	
	/**
	 * 清理测试重复数据
	 */
	public function clearData(){
		$mUser = new \model\UserModel($this->config("database/online"));
		$cond = array();
		$cond["nick"] = "你大爷";
		$data = iterator_to_array($mUser->find($cond));
		if(empty($data))
			return false;
		$mPicture = new \model\Message($this->config("database/online"));
		$mFeed = new \model\Feed($this->config("database/online"));
		$mMsq = new \model\Msq($this->config("database/online"));
		
		foreach($data as $key=>$val){
			$user_id = $val['_id']->__toString();
			$cond['user_id'] = "${user_id}";
			$mPicture->remove($cond);
			$mFeed->remove($cond);
			$mFeed->remove(array('to_user_id'=>"$user_id"));
			$mFeed->remove(array('reoly_to_uid'=>"$user_id"));
			$mMsq->remove($cond);
			$mMsq->remove(array('to_user_id'=>"$user_id"));
			$mMsq->remove(array('reoly_to_uid'=>"$user_id"));
			$mUser->remove(array("_id"=>new \MongoId($user_id)));
		}
	}
	
	public function info(){
		phpinfo();
	}
}
?>

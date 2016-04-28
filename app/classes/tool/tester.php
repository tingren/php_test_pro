<?php

namespace tool;

if ("cli" != php_sapi_name ()) {
	// header ( "HTTP/1.0 404 Not Found" );
	exit ();
}
class Tester extends \FrontBase {
	public $sid = "qhqlqv76vie5kn0ghu2rgqc2j0";
	// public $sid = "vh4el05g8j6dfvd2nb73eav483";
	// public $sid = "cvth38jmkdjuchek59mdtanq12";
	public $host = "http://dev.nczmall.com/v2";
	public $hostv1 = "http://dev.nczmall.com/v1";
	public $nczmall = "http://api.nczmall.com/v2";
	public function reg_step1() {
		$url = $this->host . "/api/member/reg_step1.json";
		$param ['sid'] = $this->sid;
		$param ['member_tel'] = "13917591267";
		pre ( $this->quickPost ( $url, $data ) );
	}
	public function reg_step2() {
		$url = $this->host . "/api/member/reg_step2.json";
		$param ['member_name'] = "secondstupid";
		$param ['member_passwd'] = "8umku";
		$param ['sid'] = $this->sid;
		$param ['captcha'] = "534245";
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 发起充值
	 */
	public function charge() {
		$url = $this->host . "/api/xinchengticket/buy.json";
		$param ['sid'] = $this->sid;
		$param ['pdr_amount'] = 3000;
		$param ['debug'] = 1;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 */
	public function addToCart() {
		$url = $this->host . "/api/cart/add.json";
		$param ['sid'] = $this->sid;
		$param ['goods_id'] = "146009";
		$param ['quantity'] = 2;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function cartIndex() {
		$url = $this->host . "/api/cart/index.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function buyStep2() {
		$url = $this->host . "/api/cart/buyStep2.json";
		// sid 2655611763
		$param ['sid'] = $this->sid;
		$param ['cart_id[0]'] = 1244;
		$param ['address_id'] = 5;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function addAddress() {
		$url = $this->host . "/api/cart/addAddress.json";
		$param = array (
				"true_name" => "张帆",
				"area_id" => 152,
				"city_id" => 39,
				"area_info" => "上海 上海市 闵行区",
				"address" => "虹梅路3888弄",
				"tel_phone" => "56555565",
				"mob_phone" => "13918888881" 
		);
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function buyStep3() {
		$url = $this->host . "/api/cart/buyStep3.json";
		$param = array ();
		$param ['sid'] = $this->sid;
		$param ['cart_id[0]'] = 1244;
		$param ['address_id'] = 74;
		/**
		 * $param['cart_id[2]'] = 1208;
		 * $param['cart_id[3]'] = 1209;
		 * $param['address_id'] = 74;*
		 */
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/*
	 *
	 */
	public function buyItNow() {
		$url = $this->host . "/api/cart/buyitnow.json";
		$param = array ();
		$param ['sid'] = $this->sid;
		$param ['goods_id'] = 125063;
		$param ['quantity'] = 1;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function getSid() {
		pre ( $this->md5ToInt ( md5 ( $this->sid ) ) );
	}
	public function removeFromCart() {
		$url = $this->host . "/api/cart/remove.json";
		$param = array ();
		$param ['sid'] = $this->sid;
		$param ['goods_id'] = 112917;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function captcha() {
		$url = $this->host . "/api/member/capatch.json";
		$param ['sid'] = $this->sid;
		$param ['member_tel'] = 13917591267;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function checkCaptcha() {
		$url = $this->host . "/api/member/checkCaptcha.json";
		$param ['sid'] = $this->sid;
		$param ['member_tel'] = 13917591267;
		$param ['captcha'] = 513144;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 */
	public function myorder() {
		$url = $this->host . "/api/history/myorder.json";
		$param ['sid'] = $this->sid;
		$param ['order_state'] = 1;
		// $param ['order_state'] = 2;
		// $param['order_state'] = 0;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 */
	public function memberinfo() {
		$url = $this->nczmall . "/api/member/info.json";
		$param ['sid'] = $this->sid;
		$param ['member_id'] = 2;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function statedesc() {
		$url = $this->host . "/api/history/state_description.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function v1session() {
		$url = $this->hostv1 . "/feedback/show_session.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 */
	public function getAddressList() {
		$url = $this->host . "/api/cart/getAddresslist.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 检查下订单详情
	 */
	public function orderDetail() {
		$url = $this->hostv1 . "/feedback/show_order.json";
		$param ['order_id'] = 344;
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 我的收藏
	 */
	public function myfavourite() {
		$url = $this->host . "/api/history/myfavourite.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 发红包
	 */
	public function sendPacket() {
		$url = $this->host . "/api/envelope/generatePacket.json";
		$param ['sid'] = $this->sid;
		$param ['amount'] = 60;
		$param ['quantity'] = 3;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 红包详细信息
	 */
	public function viewPacket() {
		$url = $this->host . "/api/envelope/packet_detail.html";
		$param ['sid'] = $this->sid;
		$param ['packet_id'] = 376;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 开红包
	 */
	public function openPacket() {
		$url = $this->host . "/api/envelope/openPacket.json";
		$param ['sid'] = $this->sid;
		$param ['packet_id'] = 249;
		$param ['mobile'] = "13044674571";
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 写入设定
	 */
	public function setting() {
		$url = $this->host . "/api/game/defaultSetting.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 抢红包
	 */
	public function luckyPacket() {
		$url = $this->host . "/api/game/lucky.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function batchLuckyPacket() {
		for($i = 0; $i < 500; ++ $i) {
			$this->luckyPacket ();
		}
	}
	public function luckyGameInfo() {
		$url = $this->host . "/api/game/info.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 */
	public function capatch_after_login() {
		$url = $this->host . "/api/member/captcha_after_thirdparty.json";
		$param ['sid'] = $this->sid;
		$param ['member_tel'] = "13917591267";
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 绑定手机
	 */
	public function bindMobile() {
		$url = $this->host . "/api/member/bind_mobile_after_thirdparty.json";
		$param ['sid'] = $this->sid;
		$param ['member_name'] = "secondnogood";
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 重置密码
	 */
	public function reset_password() {
		$url = $this->host . "/api/member/reset_password.json";
		$param ['sid'] = $this->sid;
		$param ['mobile'] = "13044674571";
		$param ['captcha'] = "765257";
		$param ['password'] = "8um8ku";
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 */
	public function message() {
		$url = $this->host . "/api/message/message_list.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 测试红包信息
	 */
	public function luckyInfo() {
		$url = $this->host . "/api/game/info.json";
		$param ['sid'] = $this->sid;
		// $param ['pre_date'] = "2016-02-22";
		// $param ['pre_seq'] = 1;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 测试支付
	 */
	public function ticketPayment() {
		$url = $this->host . "/api/xinchengticket/pay.json";
		$param ['sid'] = $this->sid;
		$param ['pdr_sn'] = "980509647966426000";
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function invitation() {
		$url = $this->host . "/api/share/shareAndInvitation.json";
		$param ['fromid'] = 2;
		$param ['mobile'] = "13917591267";
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function myinvitation() {
		$url = $this->host . "/api/share/invitation.json";
		$param ['fromid'] = 2;
		$param ['mobile'] = "13523066669";
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function alinotify() {
		$url = $this->host . "/api/notify/alipay_notify.json";
		$str = '{"action":"alipay_notify","parameter":{"discount":"0.00","payment_type":"1","subject":"\u65b0\u57ce\u732b\u8d26\u6237\u5145\u503c100.00","trade_no":"2016022421001003070275154871","buyer_email":"killerbees@sina.com","gmt_create":"2016-02-24 17:20:52","notify_type":"trade_status_sync","quantity":"1","out_trade_no":"220509649640791000","seller_id":"2088411661355105","notify_time":"2016-02-24 17:20:54","body":"\u65b0\u57ce\u732b\u8d26\u6237\u5145\u503c100.00","trade_status":"TRADE_FINISHED","is_total_fee_adjust":"N","total_fee":"0.01","gmt_payment":"2016-02-24 17:20:53","seller_email":"nczgame@nczgame.com","gmt_close":"2016-02-24 17:20:53","price":"0.01","buyer_id":"2088002066056074","notify_id":"cb903ff40379f15ddb98a330ac06376gji","use_coupon":"N","sign_type":"RSA","sign":"jhhtKpApQ+4zaswD1AxW2hB7g9GnK9dpL41Jyh08NBYJOgBxNh1sQ8chZgB9Pgd135zBQC2pUwTq\/TU03joSKMXG8FM8uIomzXSSfA9U54Vm7yGx63KIxWtQXv7dD\/jcm1YhDTiDF\/ly5n67Dlpaiez8Fi8ZSlsx1uh267SEbqQ="}}';
		$arr = json_decode ( $str, true );
		$arr = $arr ['parameter'];
		pre ( $this->quickPost ( $url, $arr ) );
	}
	public function check() {
		$str1 = "jhhtKpApQ+4zaswD1AxW2hB7g9GnK9dpL41Jyh08NBYJOgBxNh1sQ8chZgB9Pgd135zBQC2pUwTq/TU03joSKMXG8FM8uIomzXSSfA9U54Vm7yGx63KIxWtQXv7dD/jcm1YhDTiDF/ly5n67Dlpaiez8Fi8ZSlsx1uh267SEbqQ=";
		$str2 = "jhhtKpApQ+4zaswD1AxW2hB7g9GnK9dpL41Jyh08NBYJOgBxNh1sQ8chZgB9Pgd135zBQC2pUwTq/TU03joSKMXG8FM8uIomzXSSfA9U54Vm7yGx63KIxWtQXv7dD/jcm1YhDTiDF/ly5n67Dlpaiez8Fi8ZSlsx1uh267SEbqQ=";
		echo $str1 == $str2;
	}
	public function myrecharge() {
		$url = $this->host . "/api/history/myrecharge.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	/*
	 * public function logout(){
	 * $url = $this->host."/api/member/logout.json";
	 * pre($this->quickPost($url, $param));
	 * }
	 */
	public function checkSession() {
		$url = $this->host . "/api/member/ss.json";
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 */
	public function goods_detail() {
		$url = $this->host . "/api/shop/goods_detail.json";
		$param ['sid'] = $this->sid;
		$param ['goods_id'] = 125063;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 */
	public function search() {
		$url = $this->host . "/api/shop/search.json";
		$param ['sid'] = $this->sid;
		// $param['keyword']="奶瓶";
		$param ['cate_id'] = "7";
		// $param['cat_id']=19;
		$param ['pagesize'] = 3;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 */
	public function cancelPacket() {
		$url = $this->host . "/api/envelope/cancelPacket.json";
		$param ['sid'] = $this->sid;
		$param ['packet_id'] = 316;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 结算用户发送的红包
	 */
	public function clearPacket() {
		$url = $this->host . "/api/envelope/clearPacketTest.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 退回红包
	 */
	public function refundPacket() {
		$url = $this->host . "/api/envelope/refundPacketTest.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function category() {
		$url = $this->host . "/api/shop/category.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function getarr() {
		$str = 'a:5:{s:5:"time1";s:8:"12:30:00";s:5:"time2";s:8:"19:30:00";s:5:"time3";s:8:"19:48:00";s:6:"amount";s:2:"30";s:8:"quantity";s:1:"5";}';
		$arr = unserialize ( $str );
		$arr ['amount'] = 1;
		$arr ['quantity'] = 2;
		// unset($arr["time3"]);
		echo serialize ( $arr );
		// print_r($arr);die();
	}
	
	/**
	 * 结算邀请得到的币
	 */
	public function clearInventation() {
		$url = $this->host . "/api/share/testClearInvitation.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function length() {
		echo strlen ( "k7n8qq5q2dn86f3o4hodf0hsl7" );
	}
	
	/**
	 */
	public function refundGoods() {
		$url = $this->hostv1 . "/feedback/add_refund.json";
		$param ['sid'] = $this->sid;
		/*
		 * $order_id = intval ( $_REQUEST ['order_id'] );
		 * $goods_id = intval ( $_REQUEST ['goods_id'] );
		 * $buyer_id = $_SESSION ['member'] ['id'];
		 * $refund_amount = floatval ( $_REQUEST ['refund_amount'] );
		 * $goods_num = intval ( $_REQUEST ['goods_num'] ); // 退货数量
		 */
		$param ['order_id'] = 427;
		$param ['goods_id'] = 133010;
		$param ['refund_amount'] = 110;
		$param ['refund_type'] = 1;
		$param ['goods_num'] = 1;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function echoMd5() {
		echo "\n\n";
		echo md5 ( "111111" );
		echo "\n\n";
	}
	public function get_express() {
		$url = $this->hostv1 . "/feedback/get_express.json";
		$param ['sid'] = $this->sid;
		$param ['order_id'] = 452;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function get_comment() {
		$url = $this->hostv1 . "/app_goods/comments_list.json";
		$param ['sid'] = $this->sid;
		$param ['goods_id'] = 141701;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function logout() {
		$url = $this->host . "/api/member/logout.json";
		$param ['sid'] = $this->sid;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function myrefund() {
		$url = $this->hostv1 . "/feedback/myrefundAndReturn.json";
		$param ['sid'] = $this->sid;
		$param ['f'] = 1;
		// $param['goods_id'] = 141701;
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 登陆测试
	 */
	public function login() {
		$url = $this->nczmall . "/api/member/login.json";
		$param ['sid'] = $this->sid;
		$param ['proof'] = "shuaiiauhs";
		$param ['type'] = "name";
		$param ['password'] = "1qaz2wsx";
		echo md5 ( $param ['password'] );
		echo "\n\n";
		pre ( $this->quickPost ( $url, $param ) );
	}
	
	/**
	 * 登陆测试
	 */
	public function ss() {
		$url = $this->nczmall . "/api/member/sid.json";
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function app_focus() {
		echo $url = $this->nczmall . "/api/shop/focus_pic.json";
		die ();
		$param ['field'] = 1;
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function gamelist() {
		$url = $this->nczmall . "/api/shop/get_game_list.json";
		$param ['platform'] = "android";
		$param ['version'] = '1.0';
		pre ( $this->quickPost ( $url, $param ) );
	}
	public function alipaycallback() {
		$url = $this->nczmall . "/api/notify/alipay_notify.html";
		$action = '{"action":"alipay_notify","parameter":{"discount":"0.00","payment_type":"1","subject":"\u65b0\u57ce\u732b\u8d26\u6237\u5145\u503c0.10","trade_no":"2016030721001003030216445884","buyer_email":"shuaiiauhs@gmail.com","gmt_create":"2016-03-07 11:12:56","notify_type":"trade_status_sync","quantity":"1","out_trade_no":"800510664368280000","seller_id":"2088411661355105","notify_time":"2016-03-07 11:12:58","body":"\u65b0\u57ce\u732b\u8d26\u6237\u5145\u503c0.10","trade_status":"TRADE_FINISHED","is_total_fee_adjust":"N","total_fee":"0.10","gmt_payment":"2016-03-07 11:12:58","seller_email":"nczgame@nczgame.com","gmt_close":"2016-03-07 11:12:58","price":"0.10","buyer_id":"2088102102551030","notify_id":"067633e5323b847ee0a41c8158be62bg8e","use_coupon":"N","sign_type":"MD5","sign":"17cf69bab04567fe73563e005932b4ad"}}';
		$arr = json_decode($action,true);
		$param = $arr['parameter'];
		pre ($this->quickPost($url, $param));
	}
}
?>

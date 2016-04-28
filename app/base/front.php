<?php
use tool\Debug as DBG;
class FrontBase {
	public $mem;
	public $sys;
	public $root;
	public $handle;
	public $__action;
	protected $libraries;
	public $get;
	public function setHandle($obj) {
		$this->handle = $obj;
	}
	public function getHandle() {
		return $this->handle;
	}
	protected function notfound() {
		header ( "HTTP/1.0 404 Not Found" );
		die ();
	}
	public function __construct() {
		$this->libraries = new stdClass ();
		$this->fetch ();
		$this->root = "/" . $this->webroot ();
	}
	protected function token($sid) {
		return false;
	}

	/**
	 * 生成文件
	 *
	 * @param string $view
	 *        	生成页面的原始页面
	 * @param array $param
	 *        	页面需要传入的参数
	 * @param string $path
	 *        	生成页面存放的路劲和文件件名
	 * @return boolean
	 */
	protected function savepage($view, $param, $path) {
		if (empty ( $view )) {
			return false;
		}
		if (strpos ( $view, '/' )) {
			$base_path = VIEW . "/" . "${view}.php";
		} else {
			$class = strtolower ( get_class ( $this ) );
			$base_path = VIEW . "/" . $class . "/" . "${view}.php";
		}
		if (file_exists ( $base_path )) {
			unset ( $class );
			if (! empty ( $param ))
				extract ( $param );
			ob_start ();
			require (strtolower ( $base_path ));
			$str = ob_get_contents ();
			ob_end_clean ();
			$dir = dirname ( $path );
			if (! file_exists ( $dir ))
				mkdir ( $dir, 0755, true );
			file_put_contents ( $path, $str );
			return true;
		}
		return false;
	}
	protected function render($view, $param = null) {
		if (empty ( $view ))
			return false;
		$class = strtolower ( get_class ( $this ) );
		$base_path = VIEW . "/" . $class . "/" . "${view}.php";
	 	$base_path = str_replace ( "\\", "/", $base_path );
		if (file_exists ( $base_path )) {
			if (! empty ( $param ) && is_array ( $param )) {
				extract ( $param );
				unset ( $flag );
			}
			require (strtolower ( $base_path ));
		} else {
			die ( "View Not Found" );
		}
	}
	protected function html($view, $param = null) {
		if (empty ( $view ))
			return false;
		$class = strtolower ( get_class ( $this ) );
		$base_path = VIEW . "/" . $class . "/" . "${view}.php";
		$base_path = str_replace ( "\\", "/", $base_path );
		if (file_exists ( $base_path )) {
			if (! empty ( $param ) && is_array ( $param ))
				extract ( $param );
			unset ( $flag );
			ob_start ();
			require (strtolower ( $base_path ));
			$str = ob_get_contents ();
			ob_end_clean ();
			return $str;
		}
		return false;
	}

	/**
	 * 包含页面 如公共页面 footer、header .
	 *
	 * @param string $view
	 *        	包含的页面（只能包含页面）
	 * @param array $param
	 *        	页面需要传入的变量
	 * @return boolean
	 */
	protected function include_page($view, $param = null) {
		if (! $view) {
			return false;
		}
		if (strpos ( $view, '/' )) {
			$page = VIEW . "/" . "${view}.php";
		} else {
			$class = strtolower ( get_class ( $this ) );
			$page = VIEW . "/" . $class . "/" . "${view}.php";
		}
		if (file_exists ( $page )) {
			if (! empty ( $param ) && is_array ( $param )) {
				extract ( $param );
				unset ( $flag );
			}
			require (strtolower ( $page ));
		} else {
			die ( "This Page Is Not Found" );
		}
	}
	protected function cookie($name, $value = null, $life = null) {
		if ($life == - 1) {
			return setcookie ( $name, $value, time () - 1, "/", DOMAIN, false, false );
		} else {
			$left = 24 * 3600;
		}

		if (empty ( $value )) {
			return $_COOKIE [$name];
		}
		$_COOKIE [$name] = $value;
		return setcookie ( $name, $value, time () + $life, "/", DOMAIN, false, false );
	}
	protected function redirect($url) {
		echo "<script type='text/javascript'>";
		echo "window.location.href='${url}';";
		echo "</script>";
		die ();
	}
	protected function session($key, $value = null) {
		if (empty ( $key )) {
			return false;
		}
		if (empty ( $value )) {
			return $_SESSION [$key];
		}
		$_SESSION [$key] = $value;
	}
	public function __destruct() {
	}
	protected function config($config) {
		$file = CONFIG . "/$config.php";
		$tmp = explode ( "/", $config );
		$tmp = array_diff ( $tmp, array (
				null
		) );
		if (count ( $tmp ) == 0) {
			return null;
		}
		if (file_exists ( $file )) {
			require ($file);
			return $config;
		}
		require (CONFIG . "/" . $tmp [0] . "/default.php");
		return $config;
	}
	protected function __loadObject($classname, $config = null) {
		$classname = str_replace ( "/", "\\", $classname );
		$model = __loadmdlcache ( $classname );
		if (! empty ( $model )) {
			return $model;
		}
		if (class_exists ( $classname, false )) {
			$obj = new $classname ( $config );
			__addmdlcache ( $classname, $obj );
			return $obj;
		}
		// $file = APP."/m/".strtolower($classname).".php";
		// $file = str_replace("\\","/",$file);
		// if(file_exists($file)){
		// require_once($file);
		$obj = new $classname ( $config );
		__addmdlcache ( $classname, $obj );
		return $obj;
		// }
		return false;
	}
	protected function struct($classname, $config = null) {
		$config = empty ( $config ) ? "database/default" : $config;
		$config = $this->config ( $config );
		$model = $this->__loadObject ( $classname, $config );
		if (empty ( $model )) {
			return false;
		}
		$model->loader = $this;
		$model->__config ( $config );
		return $model;
	}
	protected function getHyperlink($str) {
		if (empty ( $str ))
			return false;
		$exp = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		preg_match ( $exp, $str, $out );
		return $exp [0];
	}
	protected function fetch() {
		$url = $_SERVER ['REQUEST_URI'];
		$string = parse_url ( $url );
		$query = $string ['query'];
		$tmp = explode ( "&", $query );
		if (! empty ( $tmp )) {
			foreach ( $tmp as $q ) {
				$s = explode ( "=", $q );
				$data [$s [0]] = $s [1];
			}
		}
		$_GET = $data;
		$this->get = $data;
	}
	protected function showStatic($file) {
		if (file_exists ( $file ))
			require_once ($file);
	}
	protected function msg($error, $text = null, $params = null, $stop = true) {
		$msg = new stdClass ();
		$msg->success = $error;
		if (! empty ( $text )) {
			$msg->msg = $text;
		}
		if (! empty ( $params )) {
			foreach ( $params as $key => $value ) {
				$msg->$key = $value;
			}
		}
		echo json_encode ( $msg );
		if ($stop) {
			die ();
		}
	}
	protected function webroot() {
		$uri = $_SERVER ['REQUEST_URI'];
		$uri = explode ( "/", $uri );
		$uri = array_diff ( $uri, array (
				null
		) );
		if (empty ( $uri ))
			return "/";
		$uri = array_slice ( $uri, 0, 1 );
		return $uri [0];
	}
	protected function library($classname, $param) {
		$f = strtolower ( $classname );
		$file = APP . "/module/${f}.php";

		if (! empty ( $this->libraries->$f )) {
			return $this->libraries->$f;
		}

		if (class_exists ( $classname )) {
			$this->libraries->$f = new $classname ( $param );
			return $this->libraries->$f;
		}

		if (file_exists ( $file )) {
			require_once ($file);
			$this->libraries->$f = new $classname ( $param );
			return $this->libraries->$f;
		}
		return false;
	}

	/**
	 * 打印json 数据
     * code 错误代号， 0 无错
     * info 错误信息提示
     * data 数据
	 */
	protected function exitWithJson($code =0, $info = 'success', $jsonData = array()) {

        $jsonResult = array (
            'code' => $code,
            'info' => $info,
            'data'=>$jsonData
        );
        if(is_array($code)){
            $jsonResult = array (
                'code' => 0,
                'info' => 'success',
                'data'=>$code
            );
        }
		echo json_encode ( $jsonResult );
		if (! empty ( $this->getHandle () )) {
			ob_start ();
			$target = "__after_" . $this->__action;
			if (method_exists ( $this->getHandle (), $target )) {
				$this->handle->setResult ( $jsonResult );
				$this->handle->$target ();

			}
			ob_end_clean ();
		}
		exit();
	}
	protected function feedback($success, $msg = "UNKNOW", $data = array()) {
		$rs = new stdClass ();

		$rs->success = $success;
		$rs->msg = $msg;

		if (empty ( $data )) {
			return $rs;
		}

		foreach ( $data as $key => $value ) {
			$rs->$key = $value;
		}
		return $rs;
	}
	public function sign($param, $token) {
		if (empty ( $param ))
			return false;
		unset ( $param ['signature'] );
		ksort ( $param );
		$q = array ();
		foreach ( $param as $key => $value ) {
			$q [] = "$key=$value";
		}

	 	$p1 = sha1 ( implode ( "&", $q ) );
		// $this->exitWithJson(false,"签名调试",array("p"=>$param,'str'=>implode("&",q),"sh1"=>$p1));
		return md5 ( $p1 . $token );
	}
	public function verify($param, $token, $signature) {
		if (empty ( $param ))
			return false;
		$key = $this->sign ( $param, $token );
		if (empty ( $key ))
			return false;
		return ($key == $signature);
	}
	public function oauth() {
		// --------------------基本参数接收开始--------------------//
		$param ['uid'] = $_POST ['uid'];
		$param ['token'] = $_POST ['token'];
		$param ['signature'] = $_POST ['signature'];
		if (empty ( $param ['uid'] ))
			$this->exitWithJson ( false, "没有提交UID" );

		if (empty ( $param ['token'] ))
			$this->exitWithJson ( false, "安全码没有提交" );

		if (empty ( $param ['signature'] ))
			$this->exitWithJson ( false, "签名没有提交" );
			// -------------------基本参数接受结束--------------------//

		// --------------------令牌与签名检验开始--------------------//
		$model = new \model\UserModel ( $this->config ( "database/default" ) );
		if (! $model->checkToken ( $param ['uid'], $param ['token'] )) {
			if($_POST['uid']=="561f7fd3694eb8b55970dbee"){
				DBG::log(array("action"=>"token faild",$_POST));
			}
			$this->exitWithJson ( false, "安全码无效" );

		}

		if (! $this->verify ( $_POST, $param ['token'], $param ['signature'] )) {
			if($_POST['uid']=="561f7fd3694eb8b55970dbee"){
				DBG::log(array("action"=>"signature faild",$_POST));
			}
			$this->exitWithJson ( false, "安全签名无效" );
		}
	}
	/**
	 * 获取等级名称
	 *
	 * @param number $level等级数
	 * @return string等级名称
	 */
	public function get_level_name($level = 1) {
		switch ($level) {
			case 1 :
			case 2 :
			case 3 :
				$name = "街边拍客";
				break;
			case 4 :
			case 5 :
			case 6 :
				$name = "婚庆摄影师";
				break;
			case 7 :
			case 8 :
			case 9 :
				$name = "剧组场务";
				break;
			case 10 :
				$name = "摄影助理";
				break;
			case 11 :
				$name = "专业摄影师";
				break;
			case 12 :
				$name = "微电影导演";
				break;
			case 13 :
				$name = "MV导演";
				break;
			case 14 :
				$name = "网络剧导演";
				break;
			case 15 :
				$name = "电视剧导演";
				break;
			case 16 :
				$name = "独立电影导演";
				break;
			case 17 :
				$name = "商业电影导演";
				break;
			case 18 :
				$name = "最佳新锐导演";
				break;
			case 19 :
				$name = "金像奖最佳导演";
				break;
			case 20 :
				$name = "奥斯卡最佳导演";
				break;
			default :
				$name = "街边拍客";
		}
		return $name;
	}
	/**
	 * 1.小于60分钟，则显示**分钟前
	 * （例：1分钟前、59分钟前）
	 * 2.大于等于60分钟且小于24小时，则显示**小时前（只显示小时数，分钟忽略）
	 * （例：61分钟前发帖的显示1小时前，2小时57分钟的显示2小时前，23小时59分钟的显示23小时前）
	 * 3.大于等于24小时，小于24*2=48小时，则显示1天前、（只显示天数，小时、分钟忽略）
	 * 大于等于48小时，小于24*3=72小时，则显示2天前、
	 * 大于等于72小时，小于24*4=96小时，则显示3天前
	 * 4.大于等于96小时，直接显示日期
	 * （例:现在是29日13:20，发帖日是25日9:04，则显示6月25日）
	 */
	public function show_time($time) {
		$time = intval ( $time );
		$t = floor ( (strtotime ( "now" ) - $time) / 60 );
		if(0==$t){
			return "刚刚";
		}

		if ($t < 60 && $t > 0) {
			return $t . "分钟前";
		}
		if ($t >= 60 && $t < 60 * 24) {
			return floor ( $t / 60 ) . "小时前";
		}
		if ($t >= 60 * 24 && $t < 60 * 24 * 365) {
			return floor ( $t / 60 / 24 ) . "天前";
		}

		if ($t < 60 * 24 * 365 * 100 && $t >= 60 * 24 * 365) {
			return floor ( $t / 60 / 24 / 365 ) . "年前";
		}
		return "N年前";
	}
	// 通过自动生成的'_id'获取{'$id'}，从而得到创建时的时间戳
	public function get_time_by_id($id) {
		return hexdec ( substr ( $id, 0, 8 ) );
	}

	public function generateToken($uid,$key){
		return md5(sha1($uid).sha1($key).strtotime("now"));
	}
	
	public function quickPost($url, $param,$h=null) {
		$handle = curl_init ( $url );
		$header = array('Expect:');
		$header = array();
		if(!empty($h)){
			$header = array_merge($header,$h);
		}
		curl_setopt($handle, CURLOPT_HTTPHEADER,$header);
		curl_setopt ( $handle, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $handle, CURLOPT_POST, true );
		curl_setopt ( $handle, CURLOPT_POSTFIELDS, $param );
		$result = curl_exec ( $handle );
		curl_close ( $handle );
	 	return $result;
	}
	
	
	public function getPath($obj){
		return str_replace("\\","/",get_class($obj));
	}
	
	public function md5ToInt($hash) {
		$n = 0;
		for($i = 0; $i < 4; $i ++) {
			$str = substr ( $hash, $i * 2, 2 );
			$x = intval ( $str, 16 );
			$n = $n << 8;
			$n |= $x;
		}
		return abs ( $n );
	}
	
	public  function needLogin(){
		if(empty($_SESSION['member']['id']))
			$this->exitWithJson(9999,"需要登陆一下");
	}
	
	public function getImgPrefix(){
		if("dev.nczmall.com"==$_SERVER['HTTP_HOST']){
			return "http://dev.nczmall.com/avatar/";
		}
		return UPLOAD_MY_URL."shop/avatar/";
	}
	
	/**
	 * 默认头像
	 */
	public function defaultAvatar(){
		return "http://".$_SERVER['HTTP_HOST']."/images/default.jpg";
	}
	
	
	/**
	 * 图片名修改
	 */
	public function getResizedPictureName($picture,$size){
		if(empty($picture) || empty($size))
			return "";
		$arr = explode(".", $picture);
		$new_name = $arr[0]."_".$size.".".$arr[count($arr)-1];
		return $new_name;
	}
	
	
	/**
	 * 首页焦点图的类型
	 * 		1 => '商品',
	 2 => '类目',
	 3 => '品牌',
	 4 => '搜索',
	 5 => '其他 '
	 弹框的类型
	 1 => '商品',
	 2 => '类目',
	 3 => '品牌',
	 4 => '搜索',
	 5 => '游戏',
	 6 => '充值',
	 7 => '其他 ',
	 8 => '注册'
	 */
	protected  function get_url($type,$type_value){
		if(empty($type_value))
			return "";
		
			
		switch (intval($type)){
			case 1:
				$url="http://".$_SERVER['SERVER_NAME'].'/wap/tpls/goods/index.html?goods_id='.$type_value;
				break;
			case 2:
				$url="http://".$_SERVER['SERVER_NAME'].'/wap/tpls/search/index.html?cate_id='.$type_value;
				break;
			case 3:
				$url="http://".$_SERVER['SERVER_NAME'].'/wap/tpls/search/index.html?b_id='.$type_value;
				break;
			case 4:
				$url="http://".$_SERVER['SERVER_NAME'].'/wap/tpls/search/index.html?keyword='.$type_value;
				break;
			case 5:
				$url=$type_value;
				break;
			case 6:
				$url="http://".$_SERVER['SERVER_NAME'].'/wap/tpls/pay/index.html';
				break;
			case 7:
				$url=$type_value;
				break;
		}
		return $url;
	}
	
}

?>

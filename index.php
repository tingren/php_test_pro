<?php
header ( "Access-Control-Allow-Origin: *" );
if (isset ( $_GET ["echostr"] )) { // 服务器配置用
	echo $_GET ["echostr"];
	die ();
}
/*
if (defined ( "DEBUG" )) {
	header ( 'Access-Control-Allow-Origin: *' );
}*/

error_reporting ( E_ALL & ~ E_WARNING & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT );
// error_reporting ( E_ALL );
// 基本
require_once (dirname ( __FILE__ ) . "/front_config.php");

/* SESSION 与 MEMCACHE处理 */
/* 前端和模型基类 */
require_once (APP . "/base/front.php");
require_once (APP . "/base/web.php");
require_once (APP . "/base/Common.php");
require_once (APP . "/base/modelmultimysql.php");

if (class_exists ( "MongoCollection" )) {
	require_once (APP . "/base/modelmultimongo.php");
}
if (is_php ( '5.4.0' )) {
	require_once (APP . "/base/mongosession.php");
}
require_once (APP . "/base/session.php");
date_default_timezone_set ( "Asia/Shanghai" );

// WEB模式下装载WEB前端
if ('WEB' == MODE) {
	require_once (APP . "/base/web.php");
}
require_once (APP . "/base/function.php");
/* 初始化连接 */
$_connections = array ();
$_authenticated = array ();

// 初始化模型存储变量
$_mdl = array ();
/**
 * 调用缓存中的模型
 */
function __loadmdlcache($name) {
	if (! empty ( $_mdl [$name] ))
		return $_mdl [$name];
	return null;
}

/**
 * 设置缓存中的模型
 */
function __addmdlcache($name, $class) {
	if (empty ( $name ))
		;
	return false;
	$_mdl [$name] = $class;
}
function __loadme($classname) {
	if (class_exists ( $classname, false ))
		return;
	
	$f = strtolower ( str_replace ( "\\", "/", $classname ) );
	$file = APP . "/classes/${f}.php";
	if (file_exists ( $file )) {
		require_once ($file);
		return;
	}
	// header ( 'HTTP/1.1 404 Not Found' );
}

$env = php_sapi_name ();
if ($env == 'cli') {
	$r = $argv [1];
} else {
	$r = trim ( $_GET ['r'] );
}
if (empty ( $r )) {
	if (defined ( "INDEX" )) {
		$r = INDEX;
	}
}

str_replace ( "/../", "/", $r );
$file = WEBROOT . "/" . $r . ".html";
spl_autoload_register ( "__loadme" );

if (! empty ( $r )) {
	if (file_exists ( $file )) {
		require_once ($file);
		exit ();
	}
	$path = str_replace ( "/", "\\", $r );
	$arr = explode ( "\\", $path );
	$arr = array_diff ( $arr, array (
			null 
	) );
	$action = $arr [count ( $arr ) - 1];
	array_pop ( $arr );
	$arr [count ( $arr ) - 1] = ucfirst ( $arr [count ( $arr ) - 1] );
	$controller = implode ( "\\", $arr );
	$obj = new $controller ();
	if (class_exists ( "\event\Handle" )) {
		if (method_exists ( $obj, "getHandle" )) {
			if (empty ( $obj->getHandle () )) {
				$obj->setHandle ( new \event\Handle () );
			}
		}
	}
	$obj->__action = empty ( $obj->get ['action'] ) ? $action : $obj->get ['action'];
	$obj->$action ();
}
?>

<?php

namespace tool;
class Create extends \FrontBase {
	protected $allowed;
	protected $command;
	protected function getIndex($arr = null, $key) {
		if ($arr == null)
			return false;
		$rev = array_flip ( $arr );
		return $rev [$key];
	}
	public function __construct() {
		global $argc, $argv;
		for($i = 0; $i < $argc; $i ++) {
			$this->command [$i] = $argv [$i];
		}
	}
	public function model() {
		$type = null;
		$namespace = null;
		$table = null;
		$index = null;
		if (in_array ( "--mysql", $this->command )) {
			$index = $this->getIndex ( $this->command, "--mysql" );
			$type = "mysql";
		}
		
		if (in_array ( "--mongo", $this->command )) {
			$index = $this->getIndex ( $this->command, "--mongo" );
			$type = "mongo";
		}
		
		$namespace = $this->command [$index + 1];
		$table = $this->command [$index + 2];
		
		if (empty ( $type ) || empty ( $namespace ) || empty ( $table )) {
			echo "\n";
			echo "Usage: --mysql <namespace> <table>";
			echo "\n\n";
			return;
		}
		// --------------生成模型开始----------------//
		$namespace = str_replace ( "/", "\\", $namespace );
		$arr = explode ( "\\", $namespace );
		$arr = array_diff ( $arr, array (
				null 
		) );
		$param ['classname'] = $arr [count ( $arr ) - 1];
		$param ['namespace'] = strtolower ( str_replace ( "\\" . $param ['classname'], "", $namespace ) );
		$param ['superclass'] = ($type == "mysql") ? 'ModelMultiMySQL' : 'ModelMultiMongo';
		$param ['table'] = $table;
		$str = $this->html ( "model", $param );
		// --------------生成模型结束---------------//
		
		// ---------------生成文件开始---------------//
		$dir = APP . "/classes/" . $param ['namespace'];
		$dir = str_replace ( "\\", "/", $dir );
		$file = $dir . "/" . strtolower ( $param ['classname'] ) . ".php";
		if (file_exists ( $file )) {
			echo "\n";
			echo "[WARNING]\n";
			echo "$file was existed,quit";
			echo "\n\n";
			return;
		}
		
		if (! file_exists ( $dir )) {
			mkdir ( $dir, 0755, true );
		}
		
		file_put_contents ( $file, $str );
		echo "\n $file was created!\n\n";
		// ---------------生成文件结束---------------//
	}
	public function controller() {
		$namespace = null;
		$index = null;
		if (in_array ( "--controller", $this->command )) {
			$index = $this->getIndex ( $this->command, "--controller" );
		}
		
		
		$namespace = $this->command [$index + 1];
		
		if ( empty ( $namespace ) || empty($index)) {
			echo "\n";
			echo "Usage: --controller <namespace>";
			echo "\n\n";
			return;
		}
		
		
		// --------------生成模型开始----------------//
		$namespace = str_replace ( "/", "\\", $namespace );
		$arr = explode ( "\\", $namespace );
		$arr = array_diff ( $arr, array (
				null
		) );
		$param ['classname'] = $arr [count ( $arr ) - 1];
		$param ['namespace'] = strtolower ( str_replace ( "\\" . $param ['classname'], "", $namespace ) );
		$str = $this->html ( "controller", $param );
		// --------------生成模型结束---------------//
		
		// ---------------生成文件开始---------------//
		$dir = APP . "/classes/" . $param ['namespace'];
		$dir = str_replace ( "\\", "/", $dir );
		$file = $dir . "/" . strtolower ( $param ['classname'] ) . ".php";
		if (file_exists ( $file )) {
			echo "\n";
			echo "[WARNING]\n";
			echo "$file was existed,quit";
			echo "\n";
			return;
		}
		
		if (! file_exists ( $dir )) {
			mkdir ( $dir, 0755, true );
		}
		
		file_put_contents ( $file, $str );
		echo "\n $file was created!\n\n";
	}
	public function help(){
		echo "\n";
		echo "[Create Controller]\n";
		echo "Example: php index.php tool/create/controller --controller api/user/token \n";
		echo "\n";
		echo "\n";
		echo "[Create Model]\n";
		echo "Example: php index.php tool/create/model --mysql model/user/info info \n";
		echo "\n";
		echo "Example: php index.php tool/create/model --mongo model/user/info info \n";
		echo "\n\n";
	}
	
	
	
	
}
?>
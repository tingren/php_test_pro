<?php
class Httpclient {
	public function __construct() {
	}
	
	
	public function post($url,$data){
		$rs = $this->process($url,$data,true);
		return $rs;
	}
	
	public function get($url,$data){
		$rs = $this->process($url,$data);
		return $rs;
	}
	
	
	protected function process($url,$data,$post=false){
		$handle = curl_init();
		if($post){
			curl_setopt($handle, CURLOPT_PORT, true);
		}
		curl_setopt($handle,CURLOPT_URL,$url);
		curl_setopt($handle,CURLOPT_POSTFIELDS,$data);
		curl_setopt($handle,CURLOPT_RETURNTRANSFER,true);
		$rs = curl_exec($handle);
		if($rs){
			return $rs;
		}
		return false;
	}
}
?>
<?php
namespace tool;
class Xiaoshi extends \FrontBase{
	public function quickPost($url,$param){
		$handle = curl_init($url);
		curl_setopt($handle,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($handle,CURLOPT_POST,true);
		curl_setopt($handle,CURLOPT_POSTFIELDS,$param);
		$result = curl_exec($handle);
		curl_close($handle);
		echo $result;
	}
	public function get_costomer(){
		$url = "http://www.kuaibao365.com/api/kuaibaojia/get_costomer";
		$param=array();
		$param['type']=1;
		$this->quickPost($url,$param);
		unset($param);
	}
	public function get_area(){
		$url = "http://www.kuaibao365.com/api/kuaibao/get_all_area";
		$param=array();
		$param['pid']=110100;
		$this->quickPost($url,$param);
		unset($param);
	}
	public function get_org(){
		$url = "http://www.kuaibao365.com/api/kuaibao/get_organization";
		$param=array();
		$param['com_id']=166;
		$param['lat']=34.807601;
		$param['lng']=114.336208;
		$param['city_name']='郑州';
		$this->quickPost($url,$param);
		unset($param);
	}
	public function regist(){
		$url = "http://www.kuaibao365.com/api/kuaibao/regist";
		$_SERVER['HTTP_CLIENT']='ios';
		$param=array();
		$param['name']='xiaoshi';
		$param['mobile']=1383838438;
		$param['pwd']='xiaoshi';
		$param['type']=1;
		$param['company_id']=166;
		$param['certificate_no']='00200909370783000329';
		$param['exhibition_no']='02000137070080002016012435';
		$param['nickname']='xiaoshi';
		$param['id_number']='411425199012174444';		
		$this->quickPost($url,$param);
		unset($param);
	}
	public function get_news(){
		$url = "http://kb.com/api/kuaibao/get_news";
		$param=array();
// 		$param['mobile']=18530829630;
		$this->quickPost($url,$param);
		unset($param);
	}
	
	public function manage_customer(){
		$url = "http://kb.com/api/kuaibaojia/manage_customer";
		$param=array();
		$param['uid']=89;
		$this->quickPost($url,$param);
		unset($param);
	}
	public function get_costomer(){
		$url = "http://kb.com/api/kuaibaojia/get_costomer";
		$param=array();
		// 		$param['mobile']=18530829630;
		$this->quickPost($url,$param);
		unset($param);
	}

}
?>

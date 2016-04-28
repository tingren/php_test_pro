<?php
class Util {
	protected function upload($file,$opt=null){
		if(empty($file))
			return false;
		if(is_uploaded_file($_FILES[$file]['tmp_name'])){
			$date = date("Y/m/d");
			$dir = IMAGE_DIR;
			$dir = IMAGE_DIR."/".$date;
			if(!file_exists($dir)){
				mkdir($dir,0755,true);
			}
			
			$arr_not_allow = array("js","php","exe","com");
			$ext = explode(".",$_FILES[$file]['name']);
			$ext_name = strtolower($ext[count($ext)-1]);
			if(in_array($ext,$ext_name))
				return false;


			while(true){
				$filename = md5(microtime().$_FILES[$file]['name']).".".$ext[count($ext)-1];
				if(!file_exists($dir."/".$filename)){
					if(move_uploaded_file($_FILES[$file]['tmp_name'],$dir."/".$filename)){
						return $date."/".$filename;
					}
					break;
				}
			}
			return false;
		}
	}

	protected function createCell($key){
		$start = 65;
		$count = 0;
		$repeat = 0;
		$row = 65;
		for($i=0;$i<count($key);$i++){
			$xpos = "";
			if(($i+1)%26==0){
				$repeat++;
				$count=0;
			}
			$letter = $start+$count;
			if($repeat>0){
				$xpos.= chr($row+$repeat-1);
			}
			$xpos .= chr($letter);
			$result[$key[$i]]=$xpos;
			$count++;
		}
		return $result;
	}

	function formatnum ($number) {
		if ( $number == '' ) Return "-";
		$nlen = strlen($number);
		while ( $nlen>3 ) { 
			$fNumber = ",". substr($number,$nlen-3,3).$fNumber;
			$number = substr($number,0,-3);
			$nlen = strlen($number);
		}
		if ( $nlen <= 3 ){
			$fNumber = $number.$fNumber;
		}
		return $fNumber;
	}


	public function  formatdate ($str) {
		$date = strtotime($str);
		$now = strtotime("now");
		$unit = array("second"=>"秒前","min"=>"分钟前","hour"=>"个小时前","day"=>"天前","month"=>"个月前");
		$min = 60;
		$hour = 3600;
		$day =3600*24;
		$month = 3600*24*30;
		$time = abs($date - $now);
		if($time<$min)
			return  floor($time).$unit['second'];	
		if($time<$hour)
			return  floor($time/$min).$unit['min'];		
		if($time<$day)
			return floor($time/$hour).$unit['hour'];
		if($time<$month)
			return floor($time/$day).$unit['day'];
		if($time<$year)
			return floor($time/$day).$unit['month'];
		return  date("Y-m-d H:i:s",$date);
	}
	
	public function rechargeType($type) {
		$recharge_type = '';
		switch ($type) {
			case 1: $recharge_type = '现金'; break;
			case 2: $recharge_type = '银行转账'; break;
			case 3: $recharge_type = '在线支付'; break;
			case 4: $recharge_type = '其它'; break;
		}
		
		return $recharge_type;
	}
    
    
    /**
     * 最近1天、7天、30天、60天、120天、1年的条件处理
     * @info    主要用于全景栾川系统.
     */
    public function getMap($param){
        $ct_time=strtotime(date('Y-m-d 00:00:00',time()));
        $etime=strtotime("-1 day",$ct_time);
        switch ($param) {
            case 1:
                $stime = strtotime("-1 day",$ct_time);
                break;
            case 7:
                $stime = strtotime("-8 day",$ct_time); 
                break;
            case 30:
                $stime = strtotime("-31 day",$ct_time); 
                break;
            case 60:
                $stime = strtotime("-61 day",$ct_time); 
                break;
             case 120:
                $stime = strtotime("-121 day",$ct_time); 
                break;
            case 365:
                $stime = strtotime("-356 day",$ct_time); 
                break;
            default:
                 return NULL;
                break;
        }
        $arr=array('$gte'=>new \MongoDate($stime),'$lte'=>new \MongoDate($etime));
        return $arr;
    }
}



?>
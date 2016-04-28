<?php
/**
 * 验证者类
 * Created by PhpStorm.
 * User: lxm
 * Date: 15/5/6
 * Time: 上午11:42
 */

class Validator {

    /**
     * 是否为空值
     * @param $str
     * @return bool
     */
    public static function is_empty($str){
        $str = trim($str);
        return empty($str) ? true : false;
    }

    /**
     * 数字验证
     * @param $str
     * @param string $flag int是否是整数，float是否是浮点型
     * @return bool
     */
    public static function is_num($str,$flag = 'float'){
        if(self::is_empty($str)) return false;
        if(strtolower($flag) == 'int'){
            return ((string)(int)$str === (string)$str) ? true : false;
        }else{
            return ((string)(float)$str === (string)$str) ? true : false;
        }
    }

    /**
     * 邮箱验证
     * @param $str
     * @return bool
     */
    public static function is_email($str){
        if(self::is_empty($str)) return false;
        return preg_match("/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i",$str) ? true : false;
    }

    /**
     * 手机号码验证
     * @param $str
     * @return bool
     */
    public static function is_mobile($str){
        $exp = "/^13[0-9]{1}[0-9]{8}$|15[012356789]{1}[0-9]{8}$|18[012356789]{1}[0-9]{8}$|14[57]{1}[0-9]$/";
        if(preg_match($exp,$str)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 是否为电话
     * @param $val
     * @return bool|int
     */
    public static function is_telephone($val){
        //支持国际版：$match='/^[+]?([0-9]){1,3}?[ |-]?(0[1-9]{2,3})(-| )?\d{7,8}$/'<br />
        $match='/^(0[1-9]{2,3})(-| )?\d{7,8}$/';
        if(self::is_empty($val)) return false;
        return preg_match($match,$val);

    }

    /**
     * URL验证，纯网址格式，不支持IP验证
     */
    public static function is_url($str){
        if(self::is_empty($str)) return false;
        return preg_match('#(http|https|ftp|ftps)://([w-]+.)+[w-]+(/[w-./?%&=]*)?#i',$str) ? true : false;
    }

    /**
     * 验证中文
     * @param string $str 要匹配的字符串
     * @param string $charset 编码（默认utf-8,支持gb2312）
     * @return bool
     */
    public static function is_chinese($str,$charset = 'utf-8') {
        if(self::is_empty($str)) return false;
        $match = (strtolower($charset) == 'gb2312') ? "/^[".chr(0xa1)."-".chr(0xff)."]+$/"
            : "/^[x{4e00}-x{9fa5}]+$/u";
        return preg_match($match,$str) ? true : false;
    }

    /**
     * UTF-8验证
     */
    public static function is_utf8($str){
        if(self::is_empty($str)) return false;
        return (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word)
            == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word)
            == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word)
            == true) ? true : false;
    }

    /**
     * 验证长度
     * @param string $str
     * @param int $type(方式，默认min <= $str <= max)
     * @param int $min,最小值;$max,最大值;
     * @param int $max
     * @param string $charset 字符
     * @return bool
     */
    public static function length($str,$type=3,$min=0,$max=0,$charset = 'utf-8'){
        if(self::is_empty($str)) return false;
        $len = mb_strlen($str,$charset);
        switch($type){
            case 1: //只匹配最小值
                return ($len >= $min) ? true : false;
                break;
            case 2: //只匹配最大值
                return ($max >= $len) ? true : false;
                break;
            default: //min <= $str <= max
                return (($min <= $len) && ($len <= $max)) ? true : false;
        }
    }

    /**
     * 验证密码
     * @param string $value
     * @param int $minLen
     * @param int $maxLen
     * @return bool
     */
    public static function is_password($value,$minLen=6,$maxLen=16){
        $match='/^[\\~!@#$%^&*()-_=+|{},.?\/:;\'\"\d\w]{'.$minLen.','.$maxLen.'}$/';
        $v = trim($value);
        if(empty($v))
            return false;
        return preg_match($match,$v);
    }

    /**
     * 验证用户名
     * @param string $value
     * @param int $minLen
     * @param int $maxLen
     * @param string $charset
     * @return bool
     */
    public static function is_name($value, $minLen=2, $maxLen=16, $charset='ALL'){
        if(empty($value))
            return false;
        switch($charset){
            case 'EN': $match = '/^[_\w\d]{'.$minLen.','.$maxLen.'}$/iu';
                break;
            case 'CN':$match = '/^[_\x{4e00}-\x{9fa5}\d]{'.$minLen.','.$maxLen.'}$/iu';
                break;
            default:$match = '/^[_\w\d\x{4e00}-\x{9fa5}]{'.$minLen.','.$maxLen.'}$/iu';
        }
        return preg_match($match,$value);
    }

    /**
     * 匹配日期
     * @param string $str
     * @return bool
     */
    public static function check_date($str){
        $dateArr = explode("-", $str);
        if (is_numeric($dateArr[0]) && is_numeric($dateArr[1]) && is_numeric($dateArr[2])) {
            if (($dateArr[0] >= 1000 && $dateArr[0] <= 10000) && ($dateArr[1] >= 0 && $dateArr[1] <= 12) && ($dateArr[2] >= 0 && $dateArr[2] <= 31))
                return true;
            else
                return false;
        }
        return false;
    }

    /**
     * 匹配时间
     * @param string $str
     * @return bool
     */
    public static function check_time($str){
        $timeArr = explode(":", $str);
        if (is_numeric($timeArr[0]) && is_numeric($timeArr[1]) && is_numeric($timeArr[2])) {
            if (($timeArr[0] >= 0 && $timeArr[0] <= 23) && ($timeArr[1] >= 0 && $timeArr[1] <= 59) && ($timeArr[2] >= 0 && $timeArr[2] <= 59))
                return true;
            else
                return false;
        }
        return false;
    }

    /**
     * 中英文混编的长度(中文按一位来算)
     * @param string $str
     * @return int
     */
    public static function zyw_str_len ($str) {
            preg_match_all("/./us", $str, $matches);
            return count(current($matches));
    }

    public static function check_zyw_str_len($str, $minLen = null, $maxLen = null) {
        $count = self::zyw_str_len($str);
        if($minLen !== null) {
            if($count < (int) $minLen){
                return false;
            }
        }
        if($maxLen !== null) {
            if($count > (int) $maxLen){
                return false;
            }
        }

        return true;
    }




    /**
     * 验证身份证号
     * @param type $idCard
     * @return boolean
     * 身份证号码的结构
     * 身份证号码是特征组合码，由17位数字本体码和一位校验码组成。
     * 排列顺序从左至右依此为：六位数字地址码，八位数字出生日期码，三位数字顺序码和一位数字校验码。
     * 地址码（前六位数）
     * 表示编码对象常住户口所在县（市、旗、区）的行政区划代码，按GB/T2260的规定执行。
     * 出生日期码（第七位至十四位）
     * 表示编码对象出生的年、月、日，按GB/T7408的规定执行，年、月、日代码之间不用分隔符。
     * 顺序码（第十五位至十七位）
     * 表示在同一地址码所标识的区域范围，对同年、同月、同日出生的人编定的顺序号，顺序码奇数分配给男性，偶数分配给女性。
     * 校验码（第十八位数）
     * 1.十七位数字本体码加权求和公式
     * S= SUM(Ai * Wi), i=0, ... , 16, 先对前17位数字的权求和。
     * Ai：表示第i位置上的身份证号码数字值
     * Wi：表示第i位置上的加权因子
     * Wi：7 9 10 5 8 4 2 1 6 3 7 9 10 5 8 4 2
     * 2. 计算模
     * Y = mod(S, 11)
     * 3.通过模得到对应的校验码
     * Y： 0 1 2 3 4 5 6 7 8 9 10
     * 校验码： 1 0 X 9 8 7 6 5 4 3 2 
     */
    public function check_id_card($idCard){

        // 只能是18位
        if(strlen($idCard)!=18){
            return false;
        }

        // 取出本体码
        $idCardBase = substr($idCard, 0, 17);

        // 取出校验码
        $verifyCode = substr($idCard, 17, 1);

        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);

        // 校验码对应值
        $verifyCodeList = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');

        // 根据前17位计算校验码
        $total = 0;
        for($i=0; $i<17; $i++){
            $total += substr($idCardBase, $i, 1)*$factor[$i];
        }

        // 取模
        $mod = $total % 11;

        // 比较校验码
        if($verifyCode == $verifyCodeList[$mod]){
            return true;
        }else{
            return false;
        }

    }
    
    /**
     * 判断是否为套票
     * @param string $ticket 券号
     */
    public static function is_taopiao($ticket) {
    	$num = substr($ticket, -1);
    	
    	if($num%2 != 0) {
    		return false;
    	}
    	
    	return true;
    }

}
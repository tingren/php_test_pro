<?php
namespace tool;
class Testalipay extends \FrontBase {

  //$return_url = "http://localhost/site?action=return&sale_id=$sale_id";
  //$notify_url = "http://localhost/site?action=notify&id=$uuid";
  protected $return_url;
  protected $notify_url;

  public function __construct(){
    $this->return_url = "http://".$_SERVER['HTTP_HOST']."/tool/testalipay/return.html";
    $this->notify_url = "http://".$_SERVER['HTTP_HOST']."/tool/testalipay/notify.html";

  }

  public function uuid(){
    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0010
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
  }


  public function index(){
    header("Content-type: text/html;charset=utf-8");
    $alipay_config = $this->config("alipay/default");
    $parameter = array(
    		"service" => "alipay.wap.create.direct.pay.by.user",
    		"partner" => trim($alipay_config['partner']),
    		"seller_id" => trim($alipay_config['seller_id']),
    		"payment_type"	=> 1,
    		"notify_url"	=> $this->notify_url,
    		"return_url"	=> $this->return_url,
    		"out_trade_no"	=> date("Ymdhis").rand(1,5).rand(100-999),
    		"subject"	=> "测试一下付钱",
    		"total_fee"	=> 0.1,
    		"show_url"	=> "http://dev.xinyingbao.com",
    		"body"	=> "测试支付",
    		"it_b_pay"	=> null,
    		"extern_token"	=> null,
    		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
    );
    $alipaySubmit = new \sdk\alipay\AlipaySubmit($alipay_config);
    $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "Buyit");
    echo $html_text;
  }

  public function notify(){
    $alipay_config = $this->config("alipay/default");
    $alipayNotify = new AlipayNotify($alipay_config);
    $verify_result = $alipayNotify->verifyNotify();
    if($verify_result) {
    	$out_trade_no = $_POST['out_trade_no'];
    	$trade_no = $_POST['trade_no'];
    	$trade_status = $_POST['trade_status'];
      if($_POST['trade_status'] == 'TRADE_FINISHED') {
    		//判断该笔订单是否在商户网站中已经做过处理
    			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
    			//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
    			//如果有做过处理，不执行商户的业务程序

    		//注意：
    		//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
      } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
    		//判断该笔订单是否在商户网站中已经做过处理
    			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
    			//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
    			//如果有做过处理，不执行商户的业务程序

    		//注意：
    		//付款完成后，支付宝系统发送该交易状态通知

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }

    	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

    	echo "success";		//请不要修改或删除
    	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      exit();
    }
    echo "fail";
    exit();
  }
}
?>

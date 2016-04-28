<?php
namespace tool;
class Testumeng extends \FrontBase {


  /**
   * 测试友盟信息发送
   */
  public function index(){
    $config = $this->config("umeng/default");
    $umeng = $this->library("Umeng",$config);
    $umeng->sendAndroidUnicast("Asx09hE-VtSmJS_xuMwimH8jjR-th02PgIBwKEYcoy-d");
  }


}
?>

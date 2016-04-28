<?php
/**
 * 短信接口---北京创世华信科技有限公司上海分公司短信接口
 * @date 20141204
 * @author yss
 */
class Sms {
    public function send($mobile, $content) {
        //若是生成的用户则不发短信内容.
        $subject = file_get_contents('http://www.517dv.com/user/ismk/tel/' . $mobile);
        if ($subject == '1') {
            return array('success' => true, 'msg' => '');
        }
        $content = urlencode($content);
        $userid = 446;
        $account = "jkwl032";
        $password = "jkwl032";
        $url = 'http://sh2.ipyy.com/sms.aspx?action=send&userid=' . $userid . '&account=' . $account . '&password=' . $password . '&mobile=' . $mobile . '&content=' . $content . '&sendTime=&extno=';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_NOBODY, 0);
        $data = curl_exec($curl);
        curl_close($curl);
        $xml = simplexml_load_string($data);
        $json = json_encode($xml);
        $rst = json_decode($json, TRUE);
        if ($rst['returnstatus'] === 'Success') {
            return array('success' => true, 'msg' => $rst['message']);
        } else {
            return array('success' => false, 'msg' => $rst['message']);
        }
    }
}

<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class SMSComponent extends Component {

    public $api_url = 'http://www.thsms.com/api/rest';
    public $username = 'sakorns';
    public $password = '929da5';

    public function getCredit() {
        $params['method'] = 'credit';
        $params['username'] = $this->username;
        $params['password'] = $this->password;

        $result = $this->curl($params);

        $xml = @simplexml_load_string($result);

        if (!is_object($xml)) {
            return array(FALSE, 'Respond error');
        } else {

            if ($xml->credit->status == 'success') {
                return array(TRUE, $xml->credit->status);
            } else {
                return array(FALSE, $xml->credit->message);
            }
        }
    }

    /*
     * 
     * From 0000,020000000,0890000000,DISCOUNT,NOTICE,OTP,PRIVATE,PROMOTION,SMS,SPECIAL,VIP
     * 
     */
    public function send($from = 'SMS', $to = null, $message = null) {
        $params['method'] = 'send';
        $params['username'] = $this->username;
        $params['password'] = $this->password;

        $params['from'] = $from;
        $params['to'] = $to;
        $params['message'] = $message;

        if (is_null($params['to']) || is_null($params['message'])) {
            return FALSE;
        }

        $result = $this->curl($params);
        $xml = @simplexml_load_string($result);
        if (!is_object($xml)) {
            return array(FALSE, 'Respond error');
        } else {
            if ($xml->send->status == 'success') {
                return array(TRUE, $xml->send->uuid);
            } else {
                return array(FALSE, $xml->send->message);
            }
        }
    }

    private function curl($params = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        $lastError = curl_error($ch);
        $lastReq = curl_getinfo($ch);
        curl_close($ch);

        return $response;
    }

}

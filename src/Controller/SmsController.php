<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Sms Controller
 *
 *
 * @method \App\Model\Entity\Sm[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SmsController extends AppController {

    public $Users = null;

    public function createAndSendOtpPassword() {
        $result = ['result' => false, 'msg' => 'please use POST method.'];

        if ($this->request->is(['post'])) {
            $postData = $this->request->getData();

            if (isset($postData['user_id']) && isset($postData['mobile']) && isset($postData['msg'])) {
                $this->loadComponent('SMS');
                $this->Users = TableRegistry::get('Users');
                $user = $this->Users->find()->where(['Users.id' => $postData['user_id']])->first();

                if (is_null($user)) {
                    $result['msg'] = 'not found this user.';
                } else {
                    $otpNumber = mt_rand(100000, 999999);
                    $user->otp = $otpNumber;
                    $this->Users->save($user);

                    $otpMsg = sprintf('Order Pang!,Your requested OTP is %d', $otpNumber);
                    $this->SMS->send('OTP', $postData['mobile'], $otpMsg);
                    $result = ['result' => true, 'msg' => 'success'];
                }
            } else {
                $result['msg'] = 'please check require fields[user_id,mobile,msg]';
            }
        }

        $json = json_encode($result, JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
        $json = json_encode($result, JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

}

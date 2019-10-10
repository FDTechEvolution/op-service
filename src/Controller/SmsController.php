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
        
        if ($this->request->is(['post'])) {
            $postData = $this->request->getData();
            
            if(!isset($postData['user_id'])){
                
            }
            if(!isset($postData['mobile'])){
                
            }
            if(!isset($postData['msg'])){
                
            }
            $this->loadComponent('SMS');
            $this->Users = TableRegistry::get('Users');
            
            $this->SMS->send('OTP',$postData['mobile'],$postData['msg']);
            
        }
    }

}

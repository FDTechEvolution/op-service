<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Login Controller
 *
 *
 * @method \App\Model\Entity\Login[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LoginController extends AppController {

    public $Users = null;
    
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Users = TableRegistry::get('Users');
    }

    // public function index()
    // {
    //     $result = ['result'=>false,'msg'=>'please use POST method.'];
    //     if($this->request->is(['post'])){
    //         $user = $this->Auth->identify();
    //         if ($user) {
    //             //$this->Auth->setUser($user);
    //             $result = ['result'=>true,'msg'=>'success'];
    //         }else{
    //             $result = ['result'=>false,'msg'=>'Mobile or Password is incorrect, please try again.'];
    //         }
    //     }
    // }

    public function index() {
        $result = ['result' => false, 'msg' => 'Mobile or Password is incorrect, please try again.', 'user' => []];

        if ($this->request->is(['post'])) {

            $dataPost = $this->request->getData();
            /*
              $mobile = isset($dataPost['mobile'])?$dataPost['mobile']:null;
              $password = isset($dataPost['password'])?$dataPost['password']:null;
              $resultOfCheckLogin = $this->chkLogin($mobile, $password);

              if($resultOfCheckLogin['result']){
              $result = $resultOfCheckLogin;
              }else{
              $result = $resultOfCheckLogin;
              }
             * 
             */
            $mobile = isset($dataPost['mobile'])?$dataPost['mobile']:'';
            $password = isset($dataPost['password'])?$dataPost['password']:'';
            if (strlen($password) > 0) {
                $this->loadComponent('MyAuthen');
                $password = $this->MyAuthen->hashPassword($password);
            }

            $user = $this->Users->find()->where(['Users.mobile'=>$mobile,'password'=>$password])->first();
            if (!is_null($user)) {
                $result['user'] = $user;
                $result['result'] = true;
                $result['msg'] = 'success';
            }else{
                $result['user'] = $mobile.' / '.$password;
            }
        }

        $json = json_encode($result, JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }
    
    public function getPassword($password = ''){
        $this->loadComponent('MyAuthen');
        $password = $this->MyAuthen->hashPassword($password);
        $this->log($password,'debug');
        $result['password'] = $password;
        
        $json = json_encode($result, JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }
    
    public function chkPassword($password = '',$hash=''){
        $this->loadComponent('MyAuthen');
        $result = $this->MyAuthen->verifyPassword($password,$hash);
        
        $result['result'] = $result;
        
        $json = json_encode($result, JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    private function chkLogin($mobile = '', $password = '') {
        $msg = '';
        $result = true;

        $user = $this->Users->find()
                        ->contain(['Orgs'])
                        ->where(['mobile' => $mobile, 'password' => $password])->first();
        if (!isset($user) || $user->isactive == 'D') {
            $msg = "Mobile or Password is incorrect, please try again.";
            $result = false;
        } else {
            $msg = $user;
            $result = true;
        }

        return ['result' => $result, 'msg' => $msg];
    }

}

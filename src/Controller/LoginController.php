<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
 * Login Controller
 *
 *
 * @method \App\Model\Entity\Login[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LoginController extends AppController
{
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

    public function index(){
        $result = ['result'=>false,'msg'=>'Mobile or Password is incorrect, please try again.','user'=>[]];

        if($this->request->is(['post'])){
            
            //$dataPost = $this->request->getData();
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
            $user = $this->Auth->identify();
            if($user){
                $result['user'] = $user;
                $result['result'] = true;
                $result['msg'] = 'success';
            }
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    private function chkLogin($mobile = '', $password = ''){
        $msg = '';
        $result = true;

        $user = $this->Users->find()
            ->contain(['Orgs'])
            ->where(['mobile'=>$mobile, 'password'=>$password])->first();
        if(!isset($user) || $user->isactive == 'D'){
            $msg = "Mobile or Password is incorrect, please try again.";
            $result = false;
        }else{
            $msg = $user;
            $result = true;
        }
        
        return ['result'=>$result,'msg'=>$msg];
    }

    
}

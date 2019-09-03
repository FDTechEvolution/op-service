<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
/**
 * Users Controller
 *
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        //$this->getEventManager()->off($this->Csrf); 
        //$this->Security->setConfig('unlockedActions', ['create']);
    
    }

    public function index()
    {
        $users = $this->Users->find()->where(['isactive !=' => 'D'])->toArray();
        
        $json = json_encode($users,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function get($userId = null)
    {
        $users = $this->Users->find()->where(['id'=>$userId, 'isactive !=' => 'D'])->first();
        
        $json = json_encode($users,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function create(){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $user = $this->Users->newEntity();
            $dataPost = $this->request->getData();
            $user = $this->Users->patchEntity($user, $dataPost);
        
            //Check duplicate user
            $email = isset($dataPost['email'])?$dataPost['email']:null;
            $mobile = isset($dataPost['mobile'])?$dataPost['mobile']:null;
            $resultOfCheckDup = $this->checkDuplicate($email,$mobile);
            
            if($resultOfCheckDup['result']){
                if($this->Users->save($user)){
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$user->getErrors()];
                }
            }else{
                $result = $resultOfCheckDup;
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function update($userId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $user = $this->Users->find()->where(['id'=>$userId])->first();
            $dataPost = $this->request->getData();
            $user = $this->Users->patchEntity($user, $dataPost);
        
            
            //Check duplicate user
            $email = isset($dataPost['email'])?$dataPost['email']:null;
            $mobile = isset($dataPost['mobile'])?$dataPost['mobile']:null;
            $resultOfCheckDup = $this->checkDuplicate($email,$mobile,$userId);

            if($resultOfCheckDup['result']){
                if($this->Users->save($user)){
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$user->getErrors()];
                }
            }else{
                $result = $resultOfCheckDup;
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function delete($userId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $user = $this->Users->find()->where(['id'=>$userId])->first();
            $user->isactive = 'D';
        
            if($this->Users->save($user)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$user->getErrors()];
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    /**
    * PRIVATE SECTION
    **/
    private function checkDuplicate($email = '',$mobile = '',$userId = null){
        //$this->Orgs = TableRegistry::get('Orgs');
        $msg = '';
        $result = true;

        if(is_null($userId)){
            $user = $this->Users->find()->where(['email'=>$email])->first();
            if(!is_null($user)){
                $msg = "Email of Organization can't be duplicate,";
                $result = false;
            }
            $user = $this->Users->find()->where(['mobile'=>$mobile])->first();
            if(!is_null($user)){
                $msg .= "Mobile of Organization can't be duplicate.";
                $result = false;
            }

        }else{
            $user = $this->Users->find()->where(['email'=>$email,'id !='=>$userId])->first();
            if(!is_null($user)){
                $msg = "Email of Organization can't be duplicate,";
                $result = false;
            }
            $user = $this->Users->find()->where(['mobile'=>$mobile,'id !='=>$userId])->first();
            if(!is_null($user)){
                $msg .= "Mobile of Organization can't be duplicate.";
                $result = false;
            }
        }

        return ['result'=>$result,'msg'=>$msg];
    }
}

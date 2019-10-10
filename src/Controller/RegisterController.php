<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Register Controller
 *
 *
 * @method \App\Model\Entity\Register[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RegisterController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index(){
        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $this->Users = TableRegistry::get('Users');
            $user = $this->Users->newEntity();
            $dataPost = $this->request->getData();
            $user = $this->Users->patchEntity($user, $dataPost);
        
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
}

<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
/**
 * Orgs Controller
 *
 *
 * @method \App\Model\Entity\Org[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrgsController extends AppController
{

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        //$this->getEventManager()->off($this->Csrf); 
        //$this->Security->setConfig('unlockedActions', ['create']);
    
    }


    public function index(){
        $orgs = $this->Orgs->find()->where(['isactive !='=>'D'])->toArray();
        
        $json = json_encode($orgs,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function get($orgId = null){
        $orgs = $this->Orgs->find()->where(['id'=>$orgId, 'isactive !='=>'D'])->first();
        
        $json = json_encode($orgs,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function create(){

        $result = ['result'=>false,'msg'=>'please use POST method.'];


        if($this->request->is(['post'])){

            $org = $this->Orgs->newEntity();
            $dataPost = $this->request->getData();
            $org = $this->Orgs->patchEntity($org, $dataPost);
        
            //Check duplicate org
            $name = isset($dataPost['name'])?$dataPost['name']:null;
            $code = isset($dataPost['code'])?$dataPost['code']:null;
            $resultOfCheckDup = $this->checkDuplicate($name,$code);
            
            if($resultOfCheckDup['result']){
                if($this->Orgs->save($org)){
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$org->getErrors()];
                }
            }else{
                $result = $resultOfCheckDup;
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function update($orgId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];


        if($this->request->is(['post'])){

            $org = $this->Orgs->find()->where(['id'=>$orgId])->first();
            $dataPost = $this->request->getData();
            $org = $this->Orgs->patchEntity($org, $dataPost);
        
            //Check duplicate org
            $name = isset($dataPost['name'])?$dataPost['name']:null;
            $code = isset($dataPost['code'])?$dataPost['code']:null;
            $resultOfCheckDup = $this->checkDuplicate($name,$code,$orgId);

            if($resultOfCheckDup['result']){
                if($this->Orgs->save($org)){
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$org->getErrors()];
                }
            }else{
                $result = $resultOfCheckDup;
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }




    /**
    * PRIVATE SECTION
    **/
    private function checkDuplicate($name = '',$code = '',$orgId = null){
        //$this->Orgs = TableRegistry::get('Orgs');
        $msg = '';
        $result = true;

        if(is_null($orgId)){
            $org = $this->Orgs->find()->where(['name'=>$name])->first();
            if(!is_null($org)){
                $msg = "Name of Organization can't be duplicate,";
                $result = false;
            }
            $org = $this->Orgs->find()->where(['code'=>$code])->first();
            if(!is_null($org)){
                $msg .= "Code of Organization can't be duplicate.";
                $result = false;
            }

        }else{
            $org = $this->Orgs->find()->where(['name'=>$name,'id !='=>$orgId])->first();
            if(!is_null($org)){
                $msg = "Name of Organization can't be duplicate,";
                $result = false;
            }
            $org = $this->Orgs->find()->where(['code'=>$code,'id !='=>$orgId])->first();
            if(!is_null($org)){
                $msg .= "Code of Organization can't be duplicate.";
                $result = false;
            }
        }

        return ['result'=>$result,'msg'=>$msg];
    }


}

<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\I18n\Time;
/**
 * RawOrders Controller
 *
 *
 * @method \App\Model\Entity\RawOrder[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RawOrdersController extends AppController
{
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        //$this->getEventManager()->off($this->Csrf); 
        //$this->Security->setConfig('unlockedActions', ['create']);
    
    }

    public function index($rawOrderId = null)
    {
        if(is_null($rawOrderId)){
            $rawOrder = $this->RawOrders->find()->where(['status !=' => 'DEL'])->toArray();
        }else{
            $rawOrder = $this->RawOrders->find()->where(['id'=>$rawOrderId])->first();
        }
        
        $json = json_encode($rawOrder,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function all(){
        $getOrg = $this->request->getQuery('org');
        $getLimit = $this->request->getQuery('limit');

        if(is_null($getLimit) && is_null($getOrg)){
            $rawOrder = $this->RawOrders->find()->where(['status !=' => 'DEL'])->toArray();
        }else{
            $limit = isset($getLimit)?$limit = $getLimit:$limit = 100;
            $org = isset($getOrg)?(['org_id' => $getOrg]):'';
            $resultListCondution = $this->listCondition($getLimit);

            if($resultListCondution['result']){
                $rawOrder = $this->RawOrders->find()
                        ->where([$org, 'status !=' => 'DEL'])
                        ->limit($limit)
                        ->toArray();
            }else{
                $rawOrder = $resultListCondution;
            }
        }

        $json = json_encode($rawOrder,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    private function listCondition($getLimit){
        $msg = '';
        $result = true;

        if(isset($getLimit) && !is_numeric($getLimit)){
            $msg = "Limit is be interger.";
            $result = false;
        }

        return ['result'=>$result,'msg'=>$msg];
    }

    
    public function create(){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $rawOrder = $this->RawOrders->newEntity();
            $dataPost = $this->request->getData();
            $rawOrder = $this->RawOrders->patchEntity($rawOrder, $dataPost);
            $now = Time::createFromFormat('U.u', microtime(true));
            $orderno = $now->format("ymdHisu");
            $rawOrder->orderno = $orderno;
            
            if($this->RawOrders->save($rawOrder)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$rawOrder->getErrors()];
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function delete($rawOrderId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $rawOrder = $this->RawOrders->find()->where(['id'=>$rawOrderId])->first();
            $rawOrder->status = 'DEL';
        
            if($this->RawOrders->save($rawOrder)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$rawOrder->getErrors()];
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }
}

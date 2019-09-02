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

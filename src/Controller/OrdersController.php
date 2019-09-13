<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Orders Controller
 *
 *
 * @method \App\Model\Entity\Order[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrdersController extends AppController
{
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        //$this->getEventManager()->off($this->Csrf); 
        //$this->Security->setConfig('unlockedActions', ['create']);
    
    }

    public function index()
    {
        $orders = $this->Orders->find()->where(['status !=' => 'VO'])->toArray();
        
        $json = json_encode($orders,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function list(){
        $getOrg = $this->request->getQuery('org');
        $getOrderdate = $this->request->getQuery('orderdate');
        $getLimit = $this->request->getQuery('limit');

        if(is_null($getOrderdate) && is_null($getLimit) && is_null($getOrg)){
            $order = $this->Orders->find()->where(['status !=' => 'VO'])->toArray();
        }else{
            $orderdate = isset($getOrderdate)?(['orderdate' => $getOrderdate]):'';
            $limit = isset($getLimit)?$getLimit:100;
            $org = isset($getOrg)?(['org_id' => $getOrg]):'';
            $resultListCondution = $this->listCondition($getLimit, $orderdate);

            if($resultListCondution['result']){
                $order = $this->Orders->find()
                        ->where([$orderdate, $org, 'status !=' => 'VO'])
                        ->limit($limit)
                        ->toArray();
            }else{
                $order = $resultListCondution;
            }
        }

        $json = json_encode($order,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function create(){
        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $order = $this->Orders->newEntity();
            $dataPost = $this->request->getData();
            $order = $this->Orders->patchEntity($order, $dataPost);
        
            if($this->Orders->save($order)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$user->getErrors()];
            }
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');

        return $order->order_id;
    }


    public function update($orderId = null){
        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $order = $this->Orders->find()->where(['id'=>$orderId])->first();
            $dataPost = $this->request->getData();
            $order = $this->Orders->patchEntity($order, $dataPost);

            if($this->Orders->save($order)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$order->getErrors()];
            }
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function delete($orderId = null){
        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $order = $this->Orders->find()->where(['id'=>$orderId])->first();
            $order->status = 'VO';
        
            if($this->Orders->save($order)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$order->getErrors()];
            }
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    /**
    * PRIVATE SECTION
    **/
    private function listCondition($getLimit, $orderdate){
        $msg = '';
        $result = true;

        if(isset($getLimit) && !is_numeric($getLimit)){
            $msg = "Limit is be interger.";
            $result = false;
        }
        if(isset($orderdate)){
            $chkDate = $this->Orders->find()->where([$orderdate, 'status !=' => 'VO'])->toArray();
            if(sizeof($chkDate) == 0){
                $msg = "Date not match.";
                $result = false;
            }
        }

        return ['result'=>$result,'msg'=>$msg];
    }

    
}

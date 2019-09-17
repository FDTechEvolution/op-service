<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * OrderLines Controller
 *
 *
 * @method \App\Model\Entity\OrderLine[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrderLinesController extends AppController
{
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        //$this->getEventManager()->off($this->Csrf); 
        //$this->Security->setConfig('unlockedActions', ['create']);
        $this->Orders = TableRegistry::get('Orders');
        $this->Products = TableRegistry::get('Products');
    
    }

    public function list($orderID = null){
        if(isset($orderID)){
            $getLimit = $this->request->getQuery('limit');
            $limit = isset($getLimit)?$getLimit:100;
            $orderLines = $this->OrderLines->find()->where(['order_id' => $orderID])->limit($limit)->toArray();
        }else{
            $orderLines = ['result'=>false,'msg'=>'Order is null.'];
        }

        $json = json_encode($orderLines,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function create($orderID = null){
        if(isset($orderID)){
            $result = ['result'=>false,'msg'=>'please use POST method.'];
            $totalamt = 0;

            if($this->request->is(['post'])){
                $postData = $this->request->getData();
                $order = $this->Orders->find()->where(['id' => $orderID])->first();
                foreach($postData['products'] as $key => $product_id){
                    $orderLine = $this->OrderLines->newEntity();
                    $product = $this->Products->find()->where(['id' => $product_id])->first();
                    
                    $orderLine->org_id = $order->org_id;
                    $orderLine->order_id = $orderID;
                    $orderLine->product_id = $postData['products'][$key];
                    $orderLine->qty = $postData['qtys'][$key];
                    $orderLine->price = $product->price;
                    $totalamt += $orderLine->amount = $this->amountPrice($postData['qtys'][$key],$product->price);

                    $this->OrderLines->save($orderLine);
                }
                $result = $this->setStatus($orderID, $totalamt);
            }
        }else{
            $result = ['result'=>false, 'msg'=>'Order is null.'];
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function update($orderID = null){
        if(isset($orderID)){
            $result = ['result'=>false,'msg'=>'please use POST method.'];
            $totalamt = 0;

            if($this->request->is(['post'])){
                $postData = $this->request->getData();
                foreach($postData['products'] as $key => $product_id){
                    $orderLine = $this->OrderLines->find()->where(['order_id' => $orderID, 'product_id' => $product_id])->first();
                    $product = $this->Products->find()->where(['id' => $product_id])->first();

                    if($postData['qtys'][$key] == ''){
                        $this->delete($orderLine->id);
                    }else{
                        $orderLine->product_id = $product->id;
                        $orderLine->qty = $postData['qtys'][$key];
                        $orderLine->price = $product->price;
                        $totalamt += $orderLine->amount = $this->amountPrice($postData['qtys'][$key],$product->price);
                    }

                    $this->OrderLines->save($orderLine);
                }
                $result = $this->setStatus($orderID, $totalamt);
            }
        }else{
            $result = ['result'=>false, 'msg'=>'Order is null.'];
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    /**
    * PRIVATE SECTION //////////////////////////////////////////////////////////////
    **/
    private function amountPrice($qty, $price){
        $amount = 0;
        if (is_numeric($qty) && is_numeric($price)) {
            $amount = ($qty*$price);
        }
        return $amount;
    }

    private function setStatus($orderID, $totalamt){
        $orderline = $this->OrderLines->find()->where(['order_id' => $orderID])->toArray();
            if(sizeof($orderline) != 0){
                $order = $this->Orders->get($orderID);
                $order->status = 'DX';
                $order->totalamt = $totalamt;
                if($this->Orders->save($order)){
                    return ['result'=>true,'msg'=>'success'];
                }
            }else{
                $order = $this->Orders->get($orderID);
                $order->status = 'DR';
                $order->totalamt = 0;
                if($this->Orders->save($order)){
                    return ['result'=>true, 'msg'=>'success'];
                }
            }
    }

    private function delete($orderlineID){
        $orderLine = $this->OrderLines->get($orderlineID);
        $this->OrderLines->delete($orderLine);
    }
}

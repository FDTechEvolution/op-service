<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

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
        $this->CustomerAddresses = TableRegistry::get('customer_addresses');
        //$this->getEventManager()->off($this->Csrf); 
        //$this->Security->setConfig('unlockedActions', ['create']);
    
    }

    public function index()
    {
        $orders = $this->Orders->find()->where(['status' => 'DX'])->toArray();
        
        $json = json_encode($orders,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function getOrder($stat)
    {
        $orders = $this->Orders->find()
            ->contain(['Users','OrderLines'=>['Products'],'Customers'])
            ->where(['Orders.status' => $stat])->toArray();
        $newOrders = [];
        if($orders){
            foreach($orders as $order){
                $order['user'] = $order->user->name;

                $productDes = '';
                foreach($order->order_lines as $line){
                    $productDes .= $line->product->name.' ';
                }
                $order['order_lines'] = $productDes;

                $order['customer'] = $order->customer->name;

                $line1 = '';
                $subdistrict = '';
                $district = '';
                $province = '';
                $zipcode = '';
                $addresses = $this->CustomerAddresses->find()
                    ->contain(['Addresses'])
                    ->where(['customer_addresses.customer_id' => $order->customer_id])->toArray();
                    foreach($addresses as $address){
                        $line1 .= $address->address->line1;
                        $subdistrict .= $address->address->subdistrict;
                        $district .= $address->address->district;
                        $province .= $address->address->province;
                        $zipcode .= $address->address->zipcode;
                    }

                    $order['line1'] = $line1;
                    $order['subdistrict'] = $subdistrict;
                    $order['district'] = $district;
                    $order['province'] = $province;
                    $order['zipcode'] = $zipcode;

                array_push($newOrders,$order);
            }
        }
        
        $json = json_encode($newOrders,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function orderConfirm()
    {
        $orders = $this->Orders->find()->where(['status' => 'CO'])->toArray();
        
        $json = json_encode($orders,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function orderCancel()
    {
        $orders = $this->Orders->find()->where(['status' => 'VO'])->toArray();
        
        $json = json_encode($orders,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function all(){
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
            $order->orderdate = date('Y-m-d');
            $order->status = 'CO';
        
            if($this->Orders->save($order)){
                $result = ['result'=>true,'msg'=> $order->id];
            }else{
                $result = ['result'=>false,'msg'=>$order->getErrors()];
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

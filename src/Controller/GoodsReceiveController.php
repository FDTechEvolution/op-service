<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * GoodsReceive Controller
 *
 *
 * @method \App\Model\Entity\GoodsReceive[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GoodsReceiveController extends AppController
{
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Shipments = TableRegistry::get('shipment_inouts');
        $this->Lines = TableRegistry::get('shipment_inout_lines');
        $this->Bpartners = TableRegistry::get('bpartners');
        $this->Warehouses = TableRegistry::get('warehouses');
        $this->Products = TableRegistry::get('products');
    }

    public function index()
    {

    }

    public function all($org = null) {
        $shipments = $this->Shipments->find()->where(['org_id' => $org])->toArray();

        $newShipment = [];
        if($shipments){
            foreach($shipments as $shipment){
                $bpartners = $this->Bpartners->find()->where(['id' => $shipment->bpartner_id])->first();
                $shipment['company'] = $bpartners->company;

                $warehouses = $this->Warehouses->find()->where(['id' => $shipment->to_warehouse_id])->first();
                $shipment['towarehouse'] = $warehouses->name;

                array_push($newShipment,$shipment);
            }
        }

        $json = json_encode($newShipment,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function create() {

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $shipment = $this->Shipments->newEntity();
            $dataPost = $this->request->getData();
            $shipment = $this->Shipments->patchEntity($shipment,$dataPost);
            $shipment->docdate = date('Y-m-d');
            $shipment->status = 'DR';

            if($this->Shipments->save($shipment)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$shipment->getErrors()];
            }
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function createline() {
        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $lines = $this->Lines->newEntity();
            $dataPost = $this->request->getData();
            $lines = $this->Lines->patchEntity($lines, $dataPost);

            if($this->Lines->save($lines)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$lines->getErrors()];
            }
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function shipmentline($shipment) {
        $lines = $this->Lines->find()->where(['shipment_inout_id' => $shipment])->toArray();
        $newLines = [];
        if($lines){
            foreach($lines as $line){
                $product = $this->Products->find()->where(['id' => $line->product_id])->first();
                $line['product'] = $product->name;

                array_push($newLines,$line);
            }
        }

        $json = json_encode($newLines,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function update() {

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

        }
    }

}

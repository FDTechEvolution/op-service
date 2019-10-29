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
        $this->Bpartners = TableRegistry::get('bpartners');
        $this->Warehouses = TableRegistry::get('warehouses');
    }

    public function index()
    {

    }

    public function all($org = null) {
        $shipments = $this->Shipments->find()->where(['org_id' => $org])->toArray();

        $newShipment = [];
        if($shipments){
            foreach($shipments as $shipment){
                $company = '';
                $bpartners = $this->Bpartners->find()->where(['id' => $shipment->bpartner_id])->toArray();
                foreach($bpartners as $bpartner){
                    $company = $bpartner->company;
                }
                $shipment['company'] = $company;

                $towarehouse = '';
                $warehouses = $this->Warehouses->find()->where(['id' => $shipment->to_warehouse_id])->toArray();
                foreach($warehouses as $warehouse){
                    $towarehouse = $warehouse->name;
                }
                $shipment['towarehouse'] = $towarehouse;
            }
        }

        $json = json_encode($shipment,JSON_PRETTY_PRINT);
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

    public function update() {

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

        }
    }

}

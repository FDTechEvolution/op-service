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
    }

    public function index()
    {

    }

    public function create() {

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $shipment = $this->Shipments->newEntity();
            $dataPost = $this->request->getData();
            $shipment = $this->Shipments->patchEntity($shipment,$dataPost);

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

}

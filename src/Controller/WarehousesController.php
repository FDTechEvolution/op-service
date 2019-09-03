<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
/**
 * Warehouses Controller
 *
 *
 * @method \App\Model\Entity\Warehouse[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class WarehousesController extends AppController
{
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        //$this->getEventManager()->off($this->Csrf); 
        //$this->Security->setConfig('unlockedActions', ['create']);
    
    }

    public function index()
    {
        $warehouses = $this->Warehouses->find()->where(['isactive !=' => 'D'])->toArray();
        
        $json = json_encode($warehouses,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function get($warehouseId = null)
    {
        $warehouses = $this->Warehouses->find()->where(['id'=>$warehouseId, 'isactive !=' => 'D'])->first();
        
        $json = json_encode($warehouses,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    
    public function create(){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $warehouse = $this->Warehouses->newEntity();
            $dataPost = $this->request->getData();
            $warehouse = $this->Warehouses->patchEntity($warehouse, $dataPost);
        
            //Check duplicate warehouse
            $orgId = isset($dataPost['org_id'])?$dataPost['org_id']:null;
            $name = isset($dataPost['name'])?$dataPost['name']:null;
            $resultOfCheckDup = $this->checkDuplicate($orgId,$name);
            
            if($resultOfCheckDup['result']){
                if($this->Warehouses->save($warehouse)){
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$warehouse->getErrors()];
                }
            }else{
                $result = $resultOfCheckDup;
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function update($warehouseId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $warehouse = $this->Warehouses->find()->where(['id'=>$warehouseId])->first();
            $dataPost = $this->request->getData();
            $warehouse = $this->Warehouses->patchEntity($warehouse, $dataPost);
        
            
            //Check duplicate warehouse
            $orgId = isset($dataPost['org_id'])?$dataPost['org_id']:null;
            $name = isset($dataPost['name'])?$dataPost['name']:null;
            $resultOfCheckDup = $this->checkDuplicate($orgId,$name,$warehouseId);

            if($resultOfCheckDup['result']){
                if($this->Warehouses->save($warehouse)){
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$warehouse->getErrors()];
                }
            }else{
                $result = $resultOfCheckDup;
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function delete($warehouseId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $warehouse = $this->Warehouses->find()->where(['id'=>$warehouseId])->first();
            $warehouse->isactive = 'D';
        
            if($this->Warehouses->save($warehouse)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$warehouse->getErrors()];
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }




    /**
    * PRIVATE SECTION
    **/
    private function checkDuplicate($orgId = '',$name = '',$warehouseId = null){
        $msg = '';
        $result = true;

        if(is_null($warehouseId)){
            $warehouse = $this->Warehouses->find()->where(['org_id'=>$orgId, 'name' => $name])->first();
            if(!is_null($warehouse)){
                $msg = "Warehouse name of Organization can't be duplicate.";
                $result = false;
            }

        }else{
            $warehouse = $this->Warehouses->find()->where(['org_id'=>$orgId, 'name' => $name, 'id !='=>$warehouseId])->first();
            if(!is_null($warehouse)){
                $msg = "Warehouse name of Organization can't be duplicate.";
                $result = false;
            }
        }

        return ['result'=>$result,'msg'=>$msg];
    }
}

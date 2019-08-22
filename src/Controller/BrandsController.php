<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Brands Controller
 *
 *
 * @method \App\Model\Entity\Brand[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BrandsController extends AppController
{
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        //$this->getEventManager()->off($this->Csrf); 
        //$this->Security->setConfig('unlockedActions', ['create']);
    
    }


    public function index($brandId = null)
    {
        if(is_null($brandId)){
            $brands = $this->Brands->find()->where(['isactive !=' => 'D'])->toArray();
        }else{
            $brands = $this->Brands->find()->where(['id'=>$brandId])->first();
        }
        
        $json = json_encode($brands,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function create(){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $brand = $this->Brands->newEntity();
            $dataPost = $this->request->getData();
            $brand = $this->Brands->patchEntity($brand, $dataPost);
        
            //Check duplicate user
            $name = isset($dataPost['name'])?$dataPost['name']:null;
            $orgId = isset($dataPost['org_id'])?$dataPost['org_id']:null;
            $resultOfCheckDup = $this->checkDuplicate($name, $orgId);
            
            if($resultOfCheckDup['result']){
                if($this->Brands->save($brand)){
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$brand->getErrors()];
                }
            }else{
                $result = $resultOfCheckDup;
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function update($brandId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $brand = $this->Brands->find()->where(['id'=>$brandId])->first();
            $dataPost = $this->request->getData();
            $brand = $this->Brands->patchEntity($brand, $dataPost);
        
            
            //Check duplicate brand
            $name = isset($dataPost['name'])?$dataPost['name']:null;
            $orgId = isset($dataPost['org_id'])?$dataPost['org_id']:null;
            $resultOfCheckDup = $this->checkDuplicate($name,$orgId,$brandId);

            if($resultOfCheckDup['result']){
                if($this->Brands->save($brand)){
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$brand->getErrors()];
                }
            }else{
                $result = $resultOfCheckDup;
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function delete($brandId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $brand = $this->Brands->find()->where(['id'=>$brandId])->first();
            $brand->isactive = 'D';
        
            if($this->Brands->save($brand)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$brand->getErrors()];
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }



    /**
    * PRIVATE SECTION
    **/
    private function checkDuplicate($name = '', $orgId = '', $brandId = null){
        //$this->Orgs = TableRegistry::get('Orgs');
        $msg = '';
        $result = true;

        if(is_null($brandId)){
            $brand = $this->Brands->find()->where(['name'=>$name, 'org_id'=>$orgId])->first();
            if(!is_null($brand)){
                $msg .= "Brand name of Organization can't be duplicate.";
                $result = false;
            }

        }else{
            $brand = $this->Brands->find()->where(['name'=>$name, 'org_id'=>$orgId, 'id !='=>$brandId])->first();
            if(!is_null($brand)){
                $msg .= "Brand name of Organization can't be duplicate.";
                $result = false;
            }
        }

        return ['result'=>$result,'msg'=>$msg];
    }
}

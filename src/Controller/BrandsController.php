<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

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
        $this->Products = TableRegistry::get('products');
    
    }


    public function index()
    {
        $brands = $this->Brands->find()->where(['isactive !=' => 'D'])->toArray();
        
        $json = json_encode($brands,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function get($brandId = null)
    {
        $brands = $this->Brands->find()->where(['id'=>$brandId, 'isactive !=' => 'D'])->first();
        
        $json = json_encode($brands,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function all(){
        $getOrg = $this->request->getQuery('org');
        $getActive = $this->request->getQuery('active');
        $getLimit = $this->request->getQuery('limit');

        if(is_null($getActive) && is_null($getLimit) && is_null($getOrg)){
            $brands = $this->Brands->find()->where(['status !=' => 'DEL'])->toArray();
        }else{
            $isactive = isset($getActive)?($getActive == 'yes'?(['isactive' => 'Y']):($getActive == 'no'?(['isactive' => 'N']):false)) : true;
            $limit = isset($getLimit)?$limit = $getLimit:$limit = 100;
            $org = isset($getOrg)?(['org_id' => $getOrg]):'';
            $resultListCondution = $this->listCondition($getLimit, $isactive);

            $newBrand = [];
            if($resultListCondution['result']){
                $brands = $this->Brands->find()
                        ->where([$isactive, $org, 'status !=' => 'DEL'])
                        ->limit($limit)
                        ->toArray();
                        if($brands){
                            foreach($brands as $brand){
                                $products = $this->Products->find()->where(['brand_id' => $brand->id, 'status !=' => 'DEL'])->toArray();
                                $brand['products'] = count($products);

                                array_push($newBrand,$brand);
                            }
                        }
            }else{
                $newBrand = $resultListCondution;
            }
        }

        $json = json_encode($newBrand,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    private function listCondition($getLimit, $isactive){
        $msg = '';
        $result = true;

        if(isset($getLimit) && !is_numeric($getLimit)){
            $msg = "Limit is be interger.";
            $result = false;
        }
        if(!$isactive){
            $msg = "Active status is not correct.";
            $result = false;
        }

        return ['result'=>$result,'msg'=>$msg];
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
            $brand->status = 'DEL';
        
            if($this->Brands->save($brand)){
                $products = $this->Products->find()->where(['brand_id' => $brandId])->toArray();
                foreach($products as $product){
                    $product->status = 'DEL';
                    $this->Products->save($product);
                }
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

<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
/**
 * Products Controller
 *
 *
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductsController extends AppController
{
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        //$this->getEventManager()->off($this->Csrf); 
        //$this->Security->setConfig('unlockedActions', ['create']);
    
    }

    public function index()
    {
        $products = $this->Products->find()->where(['status !=' => 'DEL'])->toArray();
        
        $json = json_encode($products,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function get($productId = null)
    {
        $products = $this->Products->find()->where(['id'=>$productId, 'status !=' => 'DEL'])->first();
        
        $json = json_encode($products,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function all(){
        $getOrg = $this->request->getQuery('org');
        $getActive = $this->request->getQuery('active');
        $getLimit = $this->request->getQuery('limit');
        $getCategory = $this->request->getQuery('category');

        if(is_null($getActive) && is_null($getLimit) && is_null($getCategory)){
            $products = $this->Products->find()->where(['status !=' => 'DEL'])->toArray();
        }else{
            $isactive = isset($getActive)?($getActive == 'yes'?(['isactive' => 'Y']):($getActive == 'no'?(['isactive' => 'N']):false)) : true;
            $limit = isset($getLimit)?$limit = $getLimit:$limit = 100;
            $category = isset($getCategory)?(['product_category_id' => $getCategory]):'';
            $org = isset($getOrg)?(['org_id' => $getOrg]):'';
            $resultListCondution = $this->listCondition($getLimit, $isactive);

            if($resultListCondution['result']){
                $products = $this->Products->find()
                        ->where([$isactive, $category, $org, 'status !=' => 'DEL'])
                        ->limit($limit)
                        ->toArray();
            }else{
                $products = $resultListCondution;
            }
        }

        $json = json_encode($products,JSON_PRETTY_PRINT);
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

            $product = $this->Products->newEntity();
            $dataPost = $this->request->getData();
            $product = $this->Products->patchEntity($product, $dataPost);
        
            //Check duplicate product
            $procateId = isset($dataPost['product_category_id'])?$dataPost['product_category_id']:null;
            $orgId = isset($dataPost['org_id'])?$dataPost['org_id']:null;
            $name = isset($dataPost['name'])?$dataPost['name']:null;
            $code = isset($dataPost['code'])?$dataPost['code']:null;
            $resultOfCheckDup = $this->checkDuplicate($procateId,$orgId,$name,$code);
            
            if($resultOfCheckDup['result']){
                if($this->Products->save($product)){
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$product->getErrors()];
                }
            }else{
                $result = $resultOfCheckDup;
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function update($productId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $product = $this->Products->find()->where(['id'=>$productId])->first();
            $dataPost = $this->request->getData();
            $product = $this->Products->patchEntity($product, $dataPost);
        
            //Check duplicate product
            $procateId = isset($dataPost['product_category_id'])?$dataPost['product_category_id']:null;
            $orgId = isset($dataPost['org_id'])?$dataPost['org_id']:null;
            $name = isset($dataPost['name'])?$dataPost['name']:null;
            $code = isset($dataPost['code'])?$dataPost['code']:null;
            $resultOfCheckDup = $this->checkDuplicate($procateId,$orgId,$name,$code,$productId);

            if($resultOfCheckDup['result']){
                if($this->Products->save($product)){
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$product->getErrors()];
                }
            }else{
                $result = $resultOfCheckDup;
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function delete($productId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $product = $this->Products->find()->where(['id'=>$productId])->first();
            $product->status = 'DEL';
        
            if($this->Products->save($product)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$product->getErrors()];
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    


    /**
    * PRIVATE SECTION
    **/
    private function checkDuplicate($procateId = '', $orgId = '', $name = '', $code = '', $productId = null){
        //$this->Orgs = TableRegistry::get('Orgs');
        $msg = '';
        $result = true;

        if(is_null($productId)){
            $product = $this->Products->find()
                            ->where([
                                'product_category_id' => $procateId,
                                'org_id' => $orgId,
                                'name' => $name,
                                'status !=' =>'DEL'
                                ])
                            ->first();
            if(!is_null($product)){
                $msg = "Product Name of Organization and Category can't be duplicate, ";
                $result = false;
            }
            $product = $this->Products->find()
                            ->where([
                                'product_category_id' => $procateId,
                                'org_id' => $orgId,
                                'code' => $code,
                                'status !=' =>'DEL'
                                ])
                            ->first();
            if(!is_null($product)){
                $msg .= "Product Code of Organization and Category can't be duplicate.";
                $result = false;
            }

        }else{
            $product = $this->Products->find()
                            ->where([
                                'product_category_id' => $procateId,
                                'org_id' => $orgId,
                                'name' => $name,
                                'status !=' => 'DEL',
                                'id !=' => $productId
                                ])
                            ->first();
            if(!is_null($product)){
                $msg = "Product Name of Organization and Category can't be duplicate, ";
                $result = false;
            }
            $product = $this->Products->find()
                            ->where([
                                'product_category_id' => $procateId,
                                'org_id' => $orgId,
                                'code' => $code,
                                'status !=' =>'DEL',
                                'id !=' => $productId
                                ])
                            ->first();
            if(!is_null($product)){
                $msg .= "Product Code of Organization and Category can't be duplicate.";
                $result = false;
            }
        }

        return ['result'=>$result,'msg'=>$msg];
    }
}

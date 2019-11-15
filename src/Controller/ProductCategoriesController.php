<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * ProductCategories Controller
 *
 *
 * @method \App\Model\Entity\ProductCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductCategoriesController extends AppController
{
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        //$this->getEventManager()->off($this->Csrf); 
        //$this->Security->setConfig('unlockedActions', ['create']);
        $this->Products = TableRegistry::get('Products');
    
    }

    public function index($procateId = null)
    {
        $productCategory = $this->ProductCategories->find()->where(['isactive !=' => 'D'])->toArray();

        $json = json_encode($productCategory,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function getcategories($orgId = null)
    {
        $productCategories = $this->ProductCategories->find()
            ->where(['org_id' => $orgId, 'isactive' => 'Y'])->toArray();
        $newProcate = [];
        if($productCategories){
            foreach($productCategories as $procate){
                $products = $this->Products->find()->where(['product_category_id' => $procate->id])->toArray();
                $procate['total'] = count($products);
                array_push($newProcate,$procate);
            }
        }

        $json = json_encode($newProcate,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function all($procateId = null)
    {
        $productCategory = $this->ProductCategories->find()->where(['id'=>$procateId, 'status !=' => 'DEL'])->first();
        
        $json = json_encode($productCategory,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function create(){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $procate = $this->ProductCategories->newEntity();
            $dataPost = $this->request->getData();
            $procate = $this->ProductCategories->patchEntity($procate, $dataPost);
        
            //Check duplicate product categories
            $name = isset($dataPost['name'])?$dataPost['name']:null;
            $orgId = isset($dataPost['org_id'])?$dataPost['org_id']:null;
            $resultOfCheckDup = $this->checkDuplicate($name,$orgId);
            
            if($resultOfCheckDup['result']){
                if($this->ProductCategories->save($procate)){
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$procate->getErrors()];
                }
            }else{
                $result = $resultOfCheckDup;
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function update($procateId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $procate = $this->ProductCategories->find()->where(['id'=>$procateId])->first();
            $dataPost = $this->request->getData();
            $procate = $this->ProductCategories->patchEntity($procate, $dataPost);
        
            
            //Check duplicate product categories
            $name = isset($dataPost['name'])?$dataPost['name']:null;
            $orgId = isset($dataPost['org_id'])?$dataPost['org_id']:null;
            $resultOfCheckDup = $this->checkDuplicate($name,$orgId,$procateId);

            if($resultOfCheckDup['result']){
                if($this->ProductCategories->save($procate)){
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$procate->getErrors()];
                }
            }else{
                $result = $resultOfCheckDup;
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function delete($procateId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $procate = $this->ProductCategories->find()->where(['id'=>$procateId])->first();
            $procate->status = 'DEL';

            if($this->ProductCategories->save($procate)){
                $products = $this->Products->find()->where(['brand_id' => $procateId])->toArray();
                foreach($products as $product){
                    $product->status = 'DEL';
                    $this->Products->save($product);
                }
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$procate->getErrors()];
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }




    /**
    * PRIVATE SECTION
    **/
    private function checkDuplicate($name = '',$orgId = '',$procateId = null){
        //$this->Orgs = TableRegistry::get('Orgs');
        $msg = '';
        $result = true;

        if(is_null($procateId)){
            $procate = $this->ProductCategories->find()->where(['name'=>$name, 'org_id'=>$orgId, 'status !=' => 'DEL'])->first();
            if(!is_null($procate)){
                $msg = "Product Category name of Organization can't be duplicate.";
                $result = false;
            }

        }else{
            $procate = $this->ProductCategories->find()->where(['name'=>$name, 'org_id'=>$orgId, 'id !='=>$procateId, 'status !=' => 'DEL'])->first();
            if(!is_null($procate)){
                $msg = "Product Category name of Organization can't be duplicate.";
                $result = false;
            }
        }

        return ['result'=>$result,'msg'=>$msg];
    }

    private function chkProductInCategories($procateId){
        $this->Products = TableRegistry::get('Products');
        $msg = '';
        $result = true;

        $product = $this->Products->find()->where(['product_category_id'=>$procateId])->first();
        if(!is_null($product)){
            $msg = "Have Product in this Category can't be delete.";
            $result = false;
        }

        return ['result'=>$result, 'msg'=>$msg];
    }
}

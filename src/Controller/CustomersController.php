<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
 * Customers Controller
 *
 *
 * @method \App\Model\Entity\Customer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CustomersController extends AppController
{
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        //$this->getEventManager()->off($this->Csrf); 
        //$this->Security->setConfig('unlockedActions', ['create']);
    
    }

    public function index($customerId = null)
    {
        if(is_null($customerId)){
            $customer = $this->Customers->find()->where(['isactive !=' => 'D'])->toArray();
        }else{
            $customer = $this->Customers->find()->where(['id'=>$customerId])->first();
        }
        
        $json = json_encode($customer,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    
    public function create(){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $customer = $this->Customers->newEntity();
            $dataPost = $this->request->getData();
            $customer = $this->Customers->patchEntity($customer, $dataPost);
        
            //Check duplicate customer
            $name = isset($dataPost['name'])?$dataPost['name']:null;
            $mobile = isset($dataPost['mobile'])?$dataPost['mobile']:null;
            $orgId = isset($dataPost['org_id'])?$dataPost['org_id']:null;
            $resultOfCheckDup = $this->checkDuplicate($name, $mobile, $orgId);

            $line1 = isset($dataPost['line1'])?$dataPost['line1']:null;
            $subdistrict = isset($dataPost['subdistrict'])?$dataPost['subdistrict']:null;
            $district = isset($dataPost['district'])?$dataPost['district']:null;
            $province = isset($dataPost['province'])?$dataPost['province']:null;
            $zipcode = isset($dataPost['zipcode'])?$dataPost['zipcode']:null;
            $resultOfAddress = $this->chkAddress($line1, $subdistrict, $district, $province, $zipcode);
            
            if($resultOfCheckDup['result'] && $resultOfAddress['result']){
                if($this->Customers->save($customer)){
                    $cusId = $customer->id;
                    $this->Addresses = TableRegistry::get('Addresses');
                    $address = $this->Addresses->newEntity();
                    $address = $this->Addresses->patchEntity($address, $dataPost);
                    if($this->Addresses->save($address)){
                        $addrId = $address->id;
                        $this->CusAddr = TableRegistry::get('Customer_Addresses');
                        $cus_addr = $this->CusAddr->newEntity();
                        $cus_addr->customer_id = $cusId;
                        $cus_addr->address_id = $addrId;
                        $cus_addr->seq = 0;
                        if($this->CusAddr->save($cus_addr)){
                            $result = ['result'=>true,'msg'=>'success'];
                        }else{
                            $result = ['result'=>false,'msg'=>$cus_addr->getErrors()];
                        }
                    }else{
                        $result = ['result'=>false,'msg'=>$address->getErrors()];
                    }
                }else{
                    $result = ['result'=>false,'msg'=>$customer->getErrors()];
                }
            }else{
                $result = [$resultOfCheckDup, $resultOfAddress];
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function update($customerId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $customer = $this->Customers->find()->where(['id'=>$customerId])->first();
            $dataPost = $this->request->getData();
            $customer = $this->Customers->patchEntity($customer, $dataPost);
        
            
            //Check duplicate customer
            $name = isset($dataPost['name'])?$dataPost['name']:null;
            $mobile = isset($dataPost['mobile'])?$dataPost['mobile']:null;
            $orgId = isset($dataPost['org_id'])?$dataPost['org_id']:null;
            $resultOfCheckDup = $this->checkDuplicate($name,$mobile,$orgId,$customerId);

            if($resultOfCheckDup['result']){
                if($this->Customers->save($customer)){
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$customer->getErrors()];
                }
            }else{
                $result = $resultOfCheckDup;
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function delete($customerId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $customer = $this->Customers->find()->where(['id'=>$customerId])->first();
            $customer->isactive = 'D';
        
            if($this->Customers->save($customer)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$customer->getErrors()];
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }




    /**
    * PRIVATE SECTION
    **/
    private function checkDuplicate($name = '', $mobile = '', $orgId = '', $customerId = null){
        $msg = '';
        $result = true;

        if(is_null($customerId)){ //create
            $customer = $this->Customers->find()->where(['org_id'=>$orgId , 'name' => $name])->first(); //chk name
            if(!is_null($customer)){
                $msg .= "Customer name of Organization can't be duplicate.";
                $result = false;
            }
            $customer = $this->Customers->find()->where(['org_id'=>$orgId , 'mobile' => $mobile])->first(); //chk mobile
            if(!is_null($customer)){
                $msg = "Customer mobile of Organization can't be duplicate,";
                $result = false;
            }

        }else{ //update
            $customer = $this->Customers->find()->where(['org_id'=>$orgId ,'name'=>$name, 'id !='=>$customerId])->first();
            if(!is_null($customer)){
                $msg = "Customer name of Organization can't be duplicate,";
                $result = false;
            }
            $customer = $this->Customers->find()->where(['org_id'=>$orgId ,'mobile'=>$mobile, 'id !='=>$customerId])->first();
            if(!is_null($customer)){
                $msg .= "Customer mobile of Organization can't be duplicate.";
                $result = false;
            }
        }
        
        return ['result'=>$result,'msg'=>$msg];
    }

    private function chkAddress($line1 = '', $subdistrict = '', $district = '', $province ='', $zipcode = ''){
        $msg = '';
        $result = true;

        if(is_null($line1) || is_null($subdistrict) || is_null($district) || is_null($province) || is_null($zipcode)){
            $msg = "Customer Address Not null.";
            $result = false;
        }

        return ['result'=>$result, 'msg'=>$msg];
    }
}

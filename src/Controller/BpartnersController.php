<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
 * Bpartners Controller
 *
 *
 * @method \App\Model\Entity\Bpartner[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BpartnersController extends AppController
{
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        //$this->getEventManager()->off($this->Csrf); 
        //$this->Security->setConfig('unlockedActions', ['create']);
    
    }

    public function index()
    {
        $bpartner = $this->Bpartners->find()->where(['isactive !=' => 'D'])->toArray();
        
        $json = json_encode($bpartner,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function all($org = null)
    {
        $bpartner = $this->Bpartners->find()->where(['org_id'=>$org, 'status !=' => 'DEL'])->toArray();
        
        $json = json_encode($bpartner,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function create(){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $bpartner = $this->Bpartners->newEntity();
            $dataPost = $this->request->getData();
            $bpartner = $this->Bpartners->patchEntity($bpartner, $dataPost);
        
            //Check duplicate bpartner
            $company = isset($dataPost['company'])?$dataPost['company']:null;
            $name = isset($dataPost['name'])?$dataPost['name']:null;
            $mobile = isset($dataPost['mobile'])?$dataPost['mobile']:null;
            $orgId = isset($dataPost['org_id'])?$dataPost['org_id']:null;
            $resultOfCheckDup = $this->checkDuplicate($company, $name, $mobile, $orgId);

            $line1 = isset($dataPost['line1'])?$dataPost['line1']:null;
            $subdistrict = isset($dataPost['subdistrict'])?$dataPost['subdistrict']:null;
            $district = isset($dataPost['district'])?$dataPost['district']:null;
            $province = isset($dataPost['province'])?$dataPost['province']:null;
            $zipcode = isset($dataPost['zipcode'])?$dataPost['zipcode']:null;
            $resultOfAddress = $this->chkAddress($line1, $subdistrict, $district, $province, $zipcode);
            
            if($resultOfCheckDup['result'] && $resultOfAddress['result']){
                if($this->Bpartners->save($bpartner)){
                    $partnerId = $bpartner->id;
                    $this->Addresses = TableRegistry::get('Addresses');
                    $address = $this->Addresses->newEntity();
                    $address = $this->Addresses->patchEntity($address, $dataPost);
                    $address->description = $dataPost['addressDescription'];
                    if($this->Addresses->save($address)){
                        $addrId = $address->id;
                        $this->bpartnerAddr = TableRegistry::get('Bpartner_Addresses');
                        $bpart_addr = $this->bpartnerAddr->newEntity();
                        $bpart_addr->bpartner_id = $partnerId;
                        $bpart_addr->address_id = $addrId;
                        $bpart_addr->seq = 0;
                        if($this->bpartnerAddr->save($bpart_addr)){
                            $result = ['result'=>true,'msg'=>'success'];
                        }else{
                            $result = ['result'=>false,'msg'=>$bpart_addr->getErrors()];
                        }
                    }else{
                        $result = ['result'=>false,'msg'=>$address->getErrors()];
                    }
                }else{
                    $result = ['result'=>false,'msg'=>$bpartner->getErrors()];
                }
            }else{
                $result = [$resultOfCheckDup, $resultOfAddress];
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function update($bpartnerId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){

            $bpartner = $this->Bpartners->find()->where(['id'=>$bpartnerId])->first();
            $dataPost = $this->request->getData();
            $bpartner = $this->Bpartners->patchEntity($bpartner, $dataPost);
        
            
            //Check duplicate customer
            $company = isset($dataPost['company'])?$dataPost['company']:null;
            $name = isset($dataPost['name'])?$dataPost['name']:null;
            $mobile = isset($dataPost['mobile'])?$dataPost['mobile']:null;
            $orgId = isset($dataPost['org_id'])?$dataPost['org_id']:null;
            $resultOfCheckDup = $this->checkDuplicate($company,$name,$mobile,$orgId,$bpartnerId);

            if($resultOfCheckDup['result']){
                if($this->Bpartners->save($bpartner)){
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$bpartner->getErrors()];
                }
            }else{
                $result = $resultOfCheckDup;
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }


    public function delete($bpartnerId = null){

        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $bpartner = $this->Bpartners->find()->where(['id'=>$bpartnerId])->first();
            $bpartner->isactive = 'D';
        
            if($this->Bpartners->save($bpartner)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$bpartner->getErrors()];
            }
            
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }




    /**
    * PRIVATE SECTION
    **/
    private function checkDuplicate($company = '', $name = '', $mobile = '', $orgId = '', $bpartnerId = null){
        $msg = '';
        $result = true;

        if(is_null($bpartnerId)){ //create
            $bpartner = $this->Bpartners->find()->where(['org_id'=>$orgId , 'company' => $company, 'status !=' => 'DEL'])->first();
            if(!is_null($bpartner)){
                $msg = "Partner company of Organization can't be duplicate, ";
                $result = false;
            }
            $bpartner = $this->Bpartners->find()->where(['org_id'=>$orgId , 'name' => $name, 'status !=' => 'DEL'])->first();
            if(!is_null($bpartner)){
                $msg .= "Partner name of Organization can't be duplicate, ";
                $result = false;
            }
            $bpartner = $this->Bpartners->find()->where(['org_id'=>$orgId , 'mobile' => $mobile, 'status !=' => 'DEL'])->first();
            if(!is_null($bpartner)){
                $msg .= "Partner mobile of Organization can't be duplicate.";
                $result = false;
            }

        }else{ //update
            $bpartner = $this->Bpartners->find()->where(['org_id'=>$orgId , 'company' => $company, 'id !='=>$bpartnerId, 'status !=' => 'DEL'])->first();
            if(!is_null($bpartner)){
                $msg = "Partner company of Organization can't be duplicate, ";
                $result = false;
            }
            $bpartner = $this->Bpartners->find()->where(['org_id'=>$orgId ,'name'=>$name, 'id !='=>$bpartnerId, 'status !=' => 'DEL'])->first();
            if(!is_null($bpartner)){
                $msg .= "Partner name of Organization can't be duplicate, ";
                $result = false;
            }
            $bpartner = $this->Bpartners->find()->where(['org_id'=>$orgId ,'mobile'=>$mobile, 'id !='=>$bpartnerId, 'status !=' => 'DEL'])->first();
            if(!is_null($bpartner)){
                $msg .= "Partner mobile of Organization can't be duplicate.";
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

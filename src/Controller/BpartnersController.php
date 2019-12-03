<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\ORM\Table;

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
        $this->BpartAddress = TableRegistry::get('bpartner_addresses');
        $this->Addresses = TableRegistry::get('addresses');
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
        $getOrg = $this->request->getQuery('org');
        $getActive = $this->request->getQuery('active');
        $getLevel = $this->request->getQuery('level');
        $getLimit = $this->request->getQuery('limit');

        if(is_null($getLevel) && is_null($getOrg)){
            $bpartner = $this->Bpartners->find()->where(['status !=' => 'DEL'])->toArray();
        }else{
            $isactive = isset($getActive)?($getActive == 'yes'?(['isactive' => 'Y']):($getActive == 'no'?(['isactive' => 'N']):false)) : true;
            $limit = isset($getLimit)?$limit = $getLimit:$limit = 100;
            $org = isset($getOrg)?(['org_id' => $getOrg]):'';
            $level = isset($getLevel)?(['level' => $getLevel]):'';
            $resultListCondution = $this->listCondition($getLimit, $isactive);

            if($resultListCondution['result']){
                $bpartner = $this->Bpartners->find()
                            ->where([$org, $level, $isactive, 'status !=' => 'DEL'])
                            ->limit($limit)
                            ->toArray();
            }else{
                $bpartner = $resultListCondution;
            }
        }
        
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
                            $result = [$resultOfCheckDup, $resultOfAddress];
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
            $bpartner->status = 'DEL';
        
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


    public function getaddress($bpartnerId = null) {
        $addrs = $this->BpartAddress->find()
                        ->contain(['addresses'])
                        ->where(['bpartner_addresses.bpartner_id' => $bpartnerId, 'status !=' => 'DEL'])
                        ->toArray();
        if($addrs) {
            $address = $addrs;
        }else{
            $address = ['result'=>false,'msg'=>$addrs->getErrors()]; 
        }

        $json = json_encode($address,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function createaddress() {
        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $address = $this->Addresses->newEntity();
            $dataPost = $this->request->getData();
            $address = $this->Addresses->patchEntity($address, $dataPost);

            if($this->Addresses->save($address)) {
                $lastID = $address->id;
                $bpart_addr = $this->BpartAddress->newEntity();
                $bpart_addr->bpartner_id = $dataPost['bpartner_id'];
                $bpart_addr->address_id = $lastID;
                $bpart_addr->seq = 0;
                if($this->BpartAddress->save($bpart_addr)) {
                    $result = ['result'=>true,'msg'=>'success'];
                }else{
                    $result = ['result'=>false,'msg'=>$bpartner->getErrors()];
                }
            }
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function updateaddress($addressID) {
        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $address = $this->Addresses->find()->where(['id' => $addressID])->first();
            $dataPost = $this->request->getData();
            $address = $this->Addresses->patchEntity($address, $dataPost);

            if($this->Addresses->save($address)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$address->getErrors()];
            }
        }

        $json = json_encode($result,JSON_PRETTY_PRINT);
        $this->set(compact('json'));
        $this->set('_serialize', 'json');
    }

    public function deleteaddress($addressID = null) {
        $result = ['result'=>false,'msg'=>'please use POST method.'];

        if($this->request->is(['post'])){
            $address = $this->Addresses->find()->where(['id'=>$addressID])->first();
            $address->status = 'DEL';
        
            if($this->Addresses->save($address)){
                $result = ['result'=>true,'msg'=>'success'];
            }else{
                $result = ['result'=>false,'msg'=>$address->getErrors()];
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
        $msg = 'success';
        $result = true;

        if(is_null($bpartnerId)){ //create
            $bpartner = $this->Bpartners->find()->where(['org_id'=>$orgId, 'company' => $company, 'name' => $name, 'mobile' => $mobile, 'status !=' => 'DEL'])->first();
            if(!is_null($bpartner)){
                $msg = "duplicated.";
                $result = false;
            }
            // $bpartner = $this->Bpartners->find()->where(['org_id'=>$orgId , 'name' => $name, 'status !=' => 'DEL'])->first();
            // if(!is_null($bpartner)){
            //     $msg .= "name duplicate.";
            //     $result = false;
            // }
            // $bpartner = $this->Bpartners->find()->where(['org_id'=>$orgId , 'mobile' => $mobile, 'status !=' => 'DEL'])->first();
            // if(!is_null($bpartner)){
            //     $msg .= "mobile duplicate.";
            //     $result = false;
            // }

        }else{ //update
            $bpartner = $this->Bpartners->find()->where(['org_id'=>$orgId , 'company' => $company, 'name' => $name, 'mobile' => $mobile, 'id !='=>$bpartnerId, 'status !=' => 'DEL'])->first();
            if(!is_null($bpartner)){
                $msg = "duplicated.";
                $result = false;
            }
            // $bpartner = $this->Bpartners->find()->where(['org_id'=>$orgId ,'name'=>$name, 'id !='=>$bpartnerId, 'status !=' => 'DEL'])->first();
            // if(!is_null($bpartner)){
            //     $msg .= "name duplicate.";
            //     $result = false;
            // }
            // $bpartner = $this->Bpartners->find()->where(['org_id'=>$orgId ,'mobile'=>$mobile, 'id !='=>$bpartnerId, 'status !=' => 'DEL'])->first();
            // if(!is_null($bpartner)){
            //     $msg .= "mobile duplicate.";
            //     $result = false;
            // }
        }
        
        return ['result'=>$result,'msg'=>$msg];
    }

    private function chkAddress($line1 = '', $subdistrict = '', $district = '', $province ='', $zipcode = ''){
        $msg = 'success';
        $result = true;

        if(is_null($line1) || is_null($subdistrict) || is_null($district) || is_null($province) || is_null($zipcode)){
            $msg = "Customer Address Not null.";
            $result = false;
        }

        return ['result'=>$result, 'msg'=>$msg];
    }

    private function listCondition($getLimit, $isactive){
        $msg = 'success';
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
}

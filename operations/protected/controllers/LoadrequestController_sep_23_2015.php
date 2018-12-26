<?php

class LoadrequestController extends Controller {
    public $truck_type;
    public $goods_type;
    public $admins;
    public function actionApprove() {
        $ids = Yii::app()->request->getParam('id');
        $approved = 0;
        foreach ($ids as $id) {
            $row = Truckloadrequest::model()->find('id_load_truck_request=' . $id);
            if (!$row->approved) {
                $custObj = Customer::model()->find('id_customer=' . $row->id_customer);
                if ($custObj->enable_sms_email_ads) {
                    $data = array('id' => '9', 'replace' => array('%customer_name%' => $obj->fullname), 'mail' => array("to" => array($custObj->email => $custObj->fullname)));
                    Mail::send($data);
                }
                $approved = 1;
                $row->approved = 1;
                $row->save(false);
            }
        }

        if ($approved) {
            Yii::app()->user->setFlash('success', 'Selected Guest approved successfully!!');
        }
        $this->redirect('index');
    }

    public function actionCreate() {
        $model['ltr'] = new Truckloadrequest;
        $model['c'] = new Customer;
        
        if (Yii::app()->request->isPostRequest && sizeof($_POST['Truck'])) {
            //echo '<pre>';print_r($_POST);EXIT;
            if($_POST['Truckloadrequest']['type']==1){
            $xCust = explode(",", $_POST['Truckloadrequest']['title']);
            $custObj = Customer::model()->find('idprefix="' . $xCust[0] . '" or mobile="' . $xCust[3] . '"');
            $id_customer = $custObj->id_customer;
                if ($custObj->id_customer) {
                    $id_customer = $custObj->id_customer;
                }else{
                    Yii::app()->user->setFlash('error', 'Invalid user!!');
                    $this->redirect('create');
                }
            }else{
                $model['c']->attributes=$_POST['Customer'];
                $model['c']->islead=0;
                $model['c']->type='G';
                $model['c']->date_created=new CDbExpression('NOW()');
                $model['c']->status=1;
                $model['c']->approved=1;
                $model['c']->save(false);
                Customer::model()->updateAll(array('idprefix'=>  Library::getIdPrefix(array('type'=>'G','id'=>$model['c']->id_customer))),'id_customer="'.$model['c']->id_customer.'"');
                $id_customer = $model['c']->id_customer;
            }        
            
            if ($id_customer) {
                
            


            foreach ($_POST['Truck'] as $data) {
                $src = Library::getGPDetails($data['source_address']);
                
                //$row = Admin::model()->getLeastAssigmentIdSearch();
                $model['ltr'] = new Truckloadrequest();
                $model['ltr']->id_customer = $id_customer;
                $model['ltr']->id_admin_created = Yii::app()->user->id;
                
                $model['ltr']->title = $data['title'];
                $model['ltr']->truck_reg_no = $data['truck_reg_no'];
                $model['ltr']->make_year = $data['make_year'];
				$model['ltr']->make_month = $data['make_month'];
                $model['ltr']->source_address = trim($src['address']);
                $model['ltr']->source_state = trim($src['state']);
                $model['ltr']->source_city = trim($src['city']) == "" ? trim($src['input']) : trim($src['city']);
                $model['ltr']->source_lat = trim($src['lat']);
                $model['ltr']->source_lng = trim($src['lng']);

                $model['ltr']->status = '1';
                $model['ltr']->approved = 1;
                $model['ltr']->add_info = $data['add_info'];
                $model['ltr']->tracking_available = $data['tracking'];
                $model['ltr']->insurance_available = $data['insurance'];
                $model['ltr']->id_truck_type = $data['id_truck_type'];
                $model['ltr']->id_goods_type = $data['id_goods_type'];
                $model['ltr']->date_available = $data['date_available'];
                $model['ltr']->expected_return = $data['expected_return'];
                $model['ltr']->date_created = new CDbExpression('NOW()');
                $model['ltr']->save(false);
                $id_truck_load_request=$model['ltr']->id_truck_load_request;
                //exit($model['ltr']->id_truck_load_request);
                $this->addDestinations(array('id'=>$id_truck_load_request,'price'=>$data['price'],'dest'=>$data['destination_address']));
            }

            //echo '<pre>';print_r($_POST);EXIT;
            Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
            $this->redirect('index');
            }else{
                Yii::app()->user->setFlash('error','Customer not registered!!');
                //exit;
                //$this->redirect($this->createUrl('load/create'));
            }
            
        }

        $records['customer'] = Customer::model()->getApprovedActiveCustomers();
 
        $this->render('create', array('records' => $records, 'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $destRow=Truckloadrequestdestinations::model()->find(array('select'=>'GROUP_CONCAT(destination_city SEPARATOR "|") AS destination_city,GROUP_CONCAT(price  SEPARATOR "|") AS price','condition'=>'id_truck_load_request="'.$id.'"'));
        $model['dest']=$destRow->destination_city;
        $model['price']=$destRow->price;
        //exit($model['dest']);
        
        
        if (Yii::app()->request->isPostRequest) {
            
            $model['ltr']->attributes = $_POST['Truckloadrequest'];
            $src = Library::getGPDetails($_POST['Truckloadrequest']['source_address']);
            $model['ltr']->source_address = trim($src['address']);
            $model['ltr']->source_state = trim($src['state']);
            $model['ltr']->source_city = trim($src['city']) == "" ? trim($src['input']) : trim($src['city']);
            $model['ltr']->source_lat = trim($src['lat']);
            $model['ltr']->source_lng = trim($src['lng']);
            //echo '<pre>';print_r($dests);print_r($model['t']->attributes);print_r($_POST);exit;
                Truckloadrequestdestinations::model()->deleteAll('id_truck_load_request="'.$id.'"');
                $this->addDestinations(array('id'=>$id,'dest'=>$_POST['Truckloadrequest']['title'],'price'=>$_POST['Truckloadrequest']['price']));
                
            if ($model['ltr']->validate()){
                $model['ltr']->save();
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
                $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
            }
        }
        
        $this->render('update', array('model' => $model));
    }
    
  public function addDestinations($data){
        $dests=explode("|",$data['dest']);
        $price=explode("|",$data['price']);
        foreach($dests as $k=>$dest){
            $dest = Library::getGPDetails(trim($dest));
            $model1=new Truckloadrequestdestinations();
            $model1->id_truck_load_request=$data['id'];
            $model1->destination_address = trim($dest['address']);
            $model1->destination_state = trim($dest['state']);
            $model1->destination_city = trim($dest['city']) == "" ? trim($dest['input']) : trim($dest['city']);
            $model1->destination_lat = trim($dest['lat']);
            $model1->destination_lng = trim($dest['lng']);  
            $model1->price=trim($price[$k]);
            $model1->save(false);

        }
    }
    public function isBookingRequestRaised($input){
        $status=0;
        if($_SESSION['id_admin_role']==10){ //accessible only to out bound calling team
            foreach($input as $row){
                if($row->booking_request){ $status=1;}
            }
        }
        return $status;
    }

    public function actionDelete() {//($id)
        $arrayRowId = is_array(Yii::app()->request->getParam('id')) ? Yii::app()->request->getParam('id') : array(
            Yii::app()->request->getParam('id'));
        if (sizeof($arrayRowId) > 0) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id_truck_load_request', $arrayRowId);

            CActiveRecord::model('Truckloadrequest')->deleteAll($criteria);
            CActiveRecord::model('Truckloadrequestdestinations')->deleteAll($criteria);

            Yii::app()->user->setFlash('success', Yii::t('common', 'message_delete_success'));
        } else {
            Yii::app()->user->setFlash('alert', Yii::t('common', 'message_checkboxValidation_alert'));
            Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
        }
        if (!isset($_GET['ajax']))
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
    }

    public function actionIndex() {
        foreach(Admin::model()->findAll() as $adminRow){
            $this->admins[$adminRow->id_admin]=$adminRow->first_name." ".$adminRow->last_name;
        }
        $model = new Truckloadrequest('searchLoad');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Truckloadrequest']))
            $model->attributes = $_GET['Truckloadrequest'];

        $this->render('index', array(
            'model' => $model,'dataSet'=>$model->searchLoad()
        ));
    }

    public function loadModel($id) {
        $model['ltr'] = Truckloadrequest::model()->find(array("condition" => "id_truck_load_request=" . $id));
        $model['c'] = Customer::model()->find(array("condition" => "id_customer=" . $model['ltr']->id_customer));

        return $model;
    }

    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'id_admin_created':
                $return = $this->admins[$data[id_admin_created]];
                break;
            
            case 'type':
                if ($data[type] == 'C') {
                    $return = 'Commission Agent';
                } else if ($data[type] == 'L') {
                    $return = 'Load Owner';
                } else if ($data[type] == 'G') {
                    $return = 'Guest';
                } else if ($data[type] == 'T') {
                    $return = 'Truck Owner';
                } else if ($data[type] == 'TR') {
                    $return = 'Transporter';
                }
                break;
            case 'least_quote':
                $row=Yii::app()->db->createCommand('select min(quote) as least_quote from {{load_truck_request_quotes}} where id_load_truck_request="'.$data['id_load_truck_request'].'"')->queryRow();
                $return = $row['least_quote'];
                break;
            
            case 'fullname':
                $truck = $data[truck_reg_no] != "" ? "[" . $data[truck_reg_no] . "]" : "";
                $return = $data[fullname] . $truck;
                break;

            case 'address':
                $return = $data['address'];
                $return.=$data['city'] != "" ? "," . $data['city'] : "";
                $return.=$data['state'] != "" ? "," . $data['state'] : "";
                break;

            case 'mobile':
                $return = $data['mobile'];
                $return.=$data['alt_mobile_1'] != "" ? "," . $data['alt_mobile_1'] : "";
                $return.=$data['alt_mobile_2'] != "" ? "," . $data['alt_mobile_2'] : "";
                $return.=$data['alt_mobile_3'] != "" ? "," . $data['alt_mobile_3'] : "";
                $return.=$data['landline'] != "" ? "," . $data['landline'] : "";
                break;
            
            case 'id_goods_type';
                return $this->goods_type[$data['id_goods_type']];
                break;
            
            case 'id_truck_type';
                return $this->truck_type[$data['id_truck_type']];
                break;
        }
        return $return;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'manufacturer-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

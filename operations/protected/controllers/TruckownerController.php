<?php

class TruckownerController extends Controller {
    public $getSmsEmailStatus;
	public $adminUser;
    public function accessRules() {
        return $this->addActions(array('Approve'));
    }

    public function actionApprove() {
        $ids = Yii::app()->request->getParam('id');
        //echo '<pre>';print_r($ids);exit;
        $approved = 0;
        foreach ($ids as $id) {
            $row = Customer::model()->find('id_customer=' . $id);
            if (!$row->approved) {
                $data = array('id' => '8', 'replace' => array('%customer_name%' => $_POST['Customer']['fullname']), 'mail' => array("to" => array($_POST['Customer']['email'] => $_POST['Customer']['fullname'])));
                Mail::send($data);
                $approved = 1;
                $row->approved = 1;
                $row->save(false);
            }
        }

        if ($approved) {
            Yii::app()->user->setFlash('success', 'Selected Truck Onwers approved successfully!!');
        }
        $this->redirect('index');
    }

    public function actionCreate() {
        $model['c'] = new Customer;
        if (Yii::app()->request->isPostRequest) {
            $model['c']->attributes = $_POST['Customer'];
            $model['c']->type='T';
            $data = $_FILES['image'];
            $data['input']['prefix'] = 'profile_' . $id . '_';
            $data['input']['path'] = Library::getMiscUploadPath();
            $data['input']['prev_file'] = $_POST['prev_file'];
            $upload = Library::fileUpload($data);
            $model['c']->profile_image = $upload['file'];
            
            $data['input']['path'] = Library::getMiscUploadPath();
            $data['input']['prev_file'] = $_POST['prev_file'];
            $upload = Library::fileUpload($data);
            $model['c']->tds_declaration_doc = $upload['file'];
			
			//echo '<pre>';print_r($model['c']->year_in_service);exit;
           
			if ($model['c']->validate()) {
                $model['c']->date_created = new CDbExpression('NOW()');
                $model['c']->password = Admin::hashPassword($_POST['Customer']['password']);
                $model['c']->save(false);
                
                $data = array('id' => '2', 'replace' => array('%customer_name%' => $_POST['Customer']['fullname']), 'mail' => array("to" => array($_POST['Customer']['email'] => $_POST['Customer']['fullname'])));
                Mail::send($data);
                
                $data = array('id' => '8', 'replace' => array('%customer_name%' => $_POST['Customer']['fullname']), 'mail' => array("to" => array($_POST['Customer']['email'] => $_POST['Customer']['fullname'])));
                Mail::send($data);
                
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
                $this->redirect('index');
            }
        }
        $this->render('create', array('model' => $model,
        ));
    }

    public function updateTruckType($data){
        $truckTypes=array();
        foreach(Trucktype::model()->findAll() as $truckTypeObj){
            $truckTypes[$truckTypeObj->id_truck_type]=array('title'=>$truckTypeObj->title,'tonnes'=>$truckTypeObj->tonnes);
        }
        
        Customervechiletypes::model()->deleteAll('id_customer="'.$data['id'].'"');
        foreach($data['id_truck_type'] as $id_truck_type){
           $obj=new Customervechiletypes;
           $obj->id_customer=$data['id'];
           $obj->title=$truckTypes[$id_truck_type]['title'];
           $obj->tonnes=$truckTypes[$id_truck_type]['tonnes'];
           $obj->id_truck_type=$id_truck_type;
           $obj->save(false);
        }
    }     
    
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (Yii::app()->request->isPostRequest) {
            //echo '<pre>';print_r($_POST);exit;
            $this->updateTruckType(array('id_truck_type'=>$_POST['id_truck_type'],'id'=>$id));
            $this->addCOptDest($id);
            
            Yii::import('application.controllers.LeadsController');
            $LeadsController=new LeadsController;
            $LeadsController->addTrucks(array('id'=>$id));
            $LeadsController->addDriver($id);
            $model['c']->attributes=$_POST['Customer'];
            //$model['c']->id_default_source_city='';
            
            $data = $_FILES['image'];
            $data['input']['prefix'] = 'profile_' . $id . '_';
            $data['input']['path'] = Library::getMiscUploadPath();

            $data['input']['prev_file'] = $_POST['prev_file'];
            $upload = Library::fileUpload($data);
            $model['c']->profile_image = $upload['file'];
            
            $data = $_FILES['tds_declaration_doc'];
            $data['input']['prefix'] = 'tds_declaration_doc_' . $id . '_';
            $data['input']['path'] = Library::getMiscUploadPath();
            $data['input']['prev_file'] = $_POST['prev_tds_declaration_doc'];
            $upload = Library::fileUpload($data);
            $model['c']->tds_declaration_doc = $upload['file'];
            
            Customerdocs::model()->setUploadMultipleImages(array('id' => $_GET['id'], 'multiImage' => $_FILES['Customerdocs'],
                    'imageData' => $_POST['Customerdocs']['upload']));
if ($_POST['Customer']['type'] == 'C') {
                    foreach ($_POST['Customer']['operating_destination_city'] as $k => $v) {
                        if ($v == '') {
                            continue;
                        }
                        $gDetails = Library::getGPDetails($v);
                        if ($gDetails['country'] == 'India') {
                            $model['cod'] = new Customeroperatingdestinations;
                            $model['cod']->id_customer = $model['c']->id_customer;
                            $model['cod']->operating_destination_city = $gDetails['city'] == '' ? $v : $gDetails['city'];
                            $model['cod']->address = $v;
                            $model['cod']->state = $gDetails['state'];
                            $model['cod']->lat = $gDetails['lat'];
                            $model['cod']->lng = $gDetails['lng'];
                            $model['cod']->save(false);
                        }
                    }
                }

            if ($model['c']->validate()) {
                if (!empty($_POST['Customer']['password'])) {
                    $model['c']->password = Admin::hashPassword($_POST['Customer']['password']);
                }else{
                    unset($model['c']->password);
                }
                if ($model['c']->save(false)) {
                    Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
                    $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
                }
            }
        }
		$model['cod'] = array();
        $model['cod'] = Customeroperatingdestinations::model()->findAll('id_customer="' . $model['c']->id_customer . '"');
        $model['f']=  Customerdocs::model()->findall('id_customer="'.$id.'"');
        $model['truckTypes']=Trucktype::model()->findAll('status=1');
        $model['cvt']=CHtml::listData(Customervechiletypes::model()->findAll('id_customer="'.(int)$_GET['id'].'"'),'id_truck_type','title');
        $model['t']=Truck::model()->findAll('id_customer='.$id);
        $model['dr']=Driver::getCustomersDriver($id);
        $this->render('update', array('model' => $model,));
    }

	public function addCOptDest($id)
    {
		
        //if ($_POST['Customer']['type'] == 'C') {
            Customeroperatingdestinations::model()->deleteAll('id_customer="'.$id.'"');
                foreach ($_POST['Customer']['operating_city'] as $k => $v) {
                        if ($v['source'] == '' || $v['destination'] == '' ) {
                                continue;
                        }

                        $gDetails1 = Library::getGPDetails($v['source']);
						$gDetails2 = Library::getGPDetails($v['destination']);
                        
						$model['cod'] = new Customeroperatingdestinations;
						$model['cod']->id_customer = $id;

						$model['cod']->source_city = $gDetails1['city'] == '' ? $gDetails1['input'] : $gDetails1['city'];
						$model['cod']->source_address = $gDetails1['input'];
						$model['cod']->source_state = $gDetails1['state'];
						$model['cod']->source_lat = $gDetails1['lat'];
						$model['cod']->source_lng = $gDetails1['lng'];

						$model['cod']->destination_city = $gDetails2['city'] == '' ? $gDetails2['input'] : $gDetails2['city'];
						$model['cod']->destination_address = $gDetails2['input'];
						$model['cod']->destination_state = $gDetails2['state'];
						$model['cod']->destination_lat = $gDetails2['lat'];
						$model['cod']->destination_lng = $gDetails2['lng'];
						$model['cod']->save(false);
                        
                }
        //}
		//echo '<pre>';print_r($_POST);exit;
    }

    public function actionDelete() {//($id)
        $arrayRowId = is_array(Yii::app()->request->getParam('id')) ? Yii::app()->request->getParam('id') : array(
            Yii::app()->request->getParam('id'));
        if (sizeof($arrayRowId) > 0) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id_customer', $arrayRowId);
            
            //delete profile images
            $custObjs=Customer::model()->findAll($criteria);
            foreach($custObjs as $custObj){
                unlink(Library::getMiscUploadPath() . $custObj->profile_image);
            }
            //delete Customer Docs
            $custDocsObjs=  Customerdocs::model()->findAll($criteria);
            foreach($custDocsObjs as $custDocObj){
                unlink(Library::getMiscUploadPath() . $custDocObj->file);
            }
            
            $trucksObj=  Truck::model()->findAll($criteria);
            foreach($trucksObj as $trucksObjRow){
               // unlink(Library::getTruckUploadPath() . $trucksObjRow->driver_driving_licence);
                unlink(Library::getTruckUploadPath() . $trucksObjRow->vehicle_insurance);
                unlink(Library::getTruckUploadPath() . $trucksObjRow->fitness_certificate);
                unlink(Library::getTruckUploadPath() . $trucksObjRow->vehicle_rc);
                CActiveRecord::model('Truckrouteprice')->deleteAll('id_truck="'.$trucksObjRow->id_truck.'"');
            }
            foreach($trucksObj as $truckObj){
                $docObjs=Truckdoc::model()->findAll('id_truck="'.$truckObj->id_truck.'"');
                foreach($docObjs as $docObj ){
                    unlink(Library::getTruckUploadPath() . $docObj->file);
                }
                CActiveRecord::model('Truckdoc')->deleteAll('id_truck="'.$truckObj->id_truck.'"');
            }
            
            CActiveRecord::model('Customer')->deleteAll($criteria);
            CActiveRecord::model('Customerlead')->deleteAll($criteria);
            CActiveRecord::model('Customerleadassignment')->deleteAll($criteria);
            CActiveRecord::model('Customerleadstatushistory')->deleteAll($criteria);
            CActiveRecord::model('Customeroperatingdestinations')->deleteAll($criteria);
            CActiveRecord::model('Customervechiletypes')->deleteAll($criteria);
            CActiveRecord::model('Customerdocs')->deleteAll($criteria);
            CActiveRecord::model('Truck')->deleteAll($criteria);
            
            
            
            Yii::app()->user->setFlash('success', Yii::t('common', 'message_delete_success'));
        } else {
            Yii::app()->user->setFlash('alert', Yii::t('common', 'message_checkboxValidation_alert'));
            Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
        }
        //exit;
        if (!isset($_GET['ajax']))
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
    }

    public function actionIndex() {
        //echo date("Y-m-d|h:i:s");
        //exit;
        $this->getSmsEmailStatus=Library::getSmsEmailStatus();
        $model = new Customer('searchCustomerTruck');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Customer']))
            $model->attributes = $_GET['Customer'];
		$this->adminUser['']="All";
		foreach(Admin::model()->findAll('status=1') as $obj){
			$this->adminUser[$obj->id_admin]=$obj->first_name." ".$obj->last_name;
		}
        $this->render('index', array(
            'model' => $model,'dataSet'=>$model->searchCustomerTruck()
        ));
    }

    public function loadModel($id) {
        $model['c'] = Customer::model()->find(array("condition" => "id_customer=" . $id));

        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'manufacturer-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
			case 'id_admin_created':
                $return=$this->adminUser[$data->id_admin_created];
                break;
            case 'enable_sms_email_ads':
                $return=$this->getSmsEmailStatus[$data->enable_sms_email_ads];
                break;
            case 'no_of_trucks':
                $return = '<a href="'.$this->createUrl('truck/index',array('cid'=>$data->id_customer)).'" target="_blank" >'.$data->no_of_trucks.'</a>';
                break;
            case 'id_customer':
                $return='<a href="#" onclick="fnupload('.$data->id_customer.');">Upload</a> | <a href="'.$this->createUrl("cagent/download",array('id'=>$data->id_customer)).'" >Download</a> ';
                break;
            case 'fullname':
                $return='<div  id="image-name-display-id">'.$data->fullname.'<div class="logo-img"><img   src="'.Library::getMiscUploadLink().$data->profile_image.'"></div></div>';
                break;
        }
        return $return;
    }
}
?>
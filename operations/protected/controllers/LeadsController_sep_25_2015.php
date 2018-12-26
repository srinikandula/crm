<?php

class LeadsController extends Controller {

    public $customerType;

    public function accessRules() {
        return $this->addActions(array('Approve', 'Updatestatus', 'Updateleadassigment'));
    }

    public function actionUpdateleadassigment() {
        if (Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest) {
            $obj = new Customerleadassignment;
            $obj->id_admin_from = Yii::app()->user->id;
            $obj->id_admin_to = $_POST['Customerleadassignment']['id_admin_to'];
            $obj->message = $_POST['Customerleadassignment']['message'];
            $obj->status = 'Document Collection';
            $obj->id_customer = $_POST['id'];
            $obj->save(false);
            //echo '<pre>';print_r($_POST);EXIT;
            Yii::app()->user->setFlash('success', "Mail sent successully to the user!!");
            echo "1";
        }
        Yii::app()->end();
    }

    public function actionUpdatestatus() {
        if (Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest) {
            $obj = new Customerleadstatushistory;
            $obj->id_admin = Yii::app()->user->id;
            $obj->message = $_POST['Customerleadstatushistory']['message'];
            $obj->status = $_POST['Customerleadstatushistory']['status'];
            $obj->id_customer = $_POST['id'];
            $obj->save(false);
            Customerlead::model()->updateAll(array('lead_status'=>$_POST['Customerleadstatushistory']['status']),'id_customer='.$_POST['id']);
            //echo '<pre>';print_r($_POST);EXIT;
            Yii::app()->user->setFlash('success', "Updated Successfully!!");
            echo "1";
        }
        Yii::app()->end();
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

    /*public function getLeastAssigmentId() {
        $row = Yii::app()->db->CreateCommand("select a.id_admin,(select count(*) from eg_customer c,eg_customer_lead cl where c.id_customer=cl.id_customer and c.islead=1 and cl.id_admin_assigned=a.id_admin and c.date_created='" . date('Y-m-d', strtotime("-1 days")) . "') as rows from eg_admin a where a.id_admin_role=10 order by rows asc")->queryRow();
        return $row;
    }*/

    public function actionCreate() {
        //echo date('Y-m-d',strtotime("-1 days"));exit;
        $model['c'] = new Customer;
        if (Yii::app()->request->isPostRequest) {
            $model['c']->attributes = $_POST['Customer'];
            $model['c']->type = $_POST['Customer']['type'];
            $data = $_FILES['image'];
            $data['input']['prefix'] = 'profile_' . $id . '_';
            $data['input']['path'] = Library::getMiscUploadPath();
            $data['input']['prev_file'] = $_POST['prev_file'];
            $upload = Library::fileUpload($data);
            $model['c']->profile_image = $upload['file'];

            if ($model['c']->validate()) {
                $model['c']->date_created = new CDbExpression('NOW()');
                $row = Admin::model()->getLeastAssigmentId();//$this->getLeastAssigmentId();
                //$row['id_admin'];
                //$model['c']->password = Admin::hashPassword($_POST['Customer']['password']);
                $model['c']->save(false);
				$this->addCOptDest($model['c']->id_customer);
                $model['cl'] = new Customerlead;
                $model['cl']->id_customer = $model['c']->id_customer;
                $model['cl']->lead_source = $_POST['Customer']['lead_source'];
                $model['cl']->lead_status = 'Initiated';
                $model['cl']->id_admin_created = Yii::app()->user->id;
                $model['cl']->id_admin_assigned = $row['id_admin'];
                $model['cl']->save(false);

                $model['cla'] = new Customerleadassignment();
                $model['cla']->id_customer = $model['c']->id_customer;
                $model['cla']->message = 'Initial Assigment';
                $model['cla']->status = 'Assigned';
                $model['cla']->id_admin_from = Yii::app()->user->id;
                $model['cla']->id_admin_to = $row['id_admin'];
                $model['cla']->save(false);

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
                //echo '<pre>';print_r($_POST);exit;
                /* $data = array('id' => '2', 'replace' => array('%customer_name%' => $_POST['Customer']['fullname']), 'mail' => array("to" => array($_POST['Customer']['email'] => $_POST['Customer']['fullname'])));
                  Mail::send($data);

                  $data = array('id' => '8', 'replace' => array('%customer_name%' => $_POST['Customer']['fullname']), 'mail' => array("to" => array($_POST['Customer']['email'] => $_POST['Customer']['fullname'])));
                  Mail::send($data); */
				//echo '<pre>';print_r($_POST);exit;
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
                $this->redirect('index');
            }
        }
        $this->render('create', array('model' => $model,
        ));
    }
    
    public function setUploadMultipleImages($data) {
        $fUploadImage = "";
        if($data['id']){
        Customerdocs::model()->deleteAll('id_customer=' . $data['id']);
        }
        foreach ($data['multiImage']['name']['upload'] as $k => $v) {
            if ($data['multiImage']['name']['upload'][$k]['image'] == "" && $data['imageData'][$k]['prev_image'] == "") {
                continue;
            }

            $fUploadImage = array("name" => $data['multiImage']['name']['upload'][$k]['image'],
                "type" => $data['multiImage']['type']['upload'][$k]['image'],
                "tmp_name" => $data['multiImage']['tmp_name']['upload'][$k]['image'],
                "error" => $data['multiImage']['error']['upload'][$k]['image'],
                "size" => $data['multiImage']['size']['upload'][$k]['image'],);

            $fUploadImage['input']['prefix'] = 'customer_doc_'. '-' . $data['id'] . '_' . $k . '_';
            $fUploadImage['input']['path'] = Library::getMiscUploadPath();
            $fUploadImage['input']['prev_file'] = $data['imageData'][$k]['prev_image'];

            $uploadImage = Library::fileUpload($fUploadImage);
            $model = new Customerdocs;
            $model->id_customer = $data['id'];
            $model->file = $uploadImage['file'];
            $model->save(false);
        }
    }
    
    
    public function addTrucks($data){
        Truck::model()->deleteAll('id_customer='.$data['id']);
        foreach($_POST['Truck'] as $k=>$v){
            $model=new Truck;
            //$model->truck_reg_no=$v['truck_reg_no'];
            $model->attributes=$v;
            $model->id_customer=$data['id'];
            $gDetails = Library::getGPDetails($v['source_address']);
            $model->source_address=$v['source_address'];
            $model->source_city=$gDetails['city']==""?$v['source_address']:$gDetails['city'];
            $model->source_state=$gDetails['state'];
            $model->source_lat=$gDetails['lat'];
            $model->source_lng=$gDetails['lng'];
            $model->save(false);
        }
        //echo '<pre>';print_r($_POST['Truck']);
        //exit;
    }
    
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (Yii::app()->request->isPostRequest) {
            //echo '<pre>';print_r($_FILES); print_r($_REQUEST);exit;
            $this->addCOptDest($id);
			$model['c']->attributes = $_POST['Customer'];
            //$model['c']->id_default_source_city='';
            if($_POST['Customer']['type']=='T' || $_POST['Customer']['type']=='C'){
                $this->addTrucks(array('id'=>$id));
            }
            
            $data = $_FILES['image'];
            $data['input']['prefix'] = 'profile_' . $id . '_';
            $data['input']['path'] = Library::getMiscUploadPath();

            $data['input']['prev_file'] = $_POST['prev_file'];
            $upload = Library::fileUpload($data);
            $model['c']->profile_image = $upload['file'];
            
            if ($model['c']->validate()) {
                $this->setUploadMultipleImages(array('id' => $_GET['id'], 'multiImage' => $_FILES['Customerdocs'],
                    'imageData' => $_POST['Customerdocs']['upload']));
                $update_msg=Yii::t('common', 'message_modify_success');
                if ($model['c']->approved) {
                    $model['c']->islead=0;
                    $model['c']->idprefix=Library::getIdPrefix(array('type'=>$_POST['Customer']['type'],'id'=>$model['c']->id_customer));//$_POST['Customer']['type'].date(y).$model['c']->id_customer;
                    $password=Library::randomPassword();
                    $model['c']->password = Admin::hashPassword($password);
                
                    $clshObj=new Customerleadstatushistory;
                    $clshObj->id_admin=Yii::app()->user->id;
                    $clshObj->id_customer=$model['c']->id_customer;
                    $clshObj->status='Approved';
                    $clshObj->save(false);
                    $data = array('id' => '8', 'replace' => array('%password%' => $password,'%email%' => $_POST['Customer']['email'],'%mobile%' => $_POST['Customer']['mobile'],'%username%' => $_POST['Customer']['mobile'].' or '.$_POST['Customer']['email']), 'mail' => array("to" => array($_POST['Customer']['email'] => $_POST['Customer']['fullname'])));
                    Mail::send($data);
                
                    $update_msg='Updated and Approved successfully!!';
                }
                if ($model['c']->save(false)) {
                    Customerlead::model()->updateAll(array('lead_source'=>$_POST['Customer']['lead_source']),'id_customer='.$model['c']->id_customer);
                    Yii::app()->user->setFlash('success', $update_msg);
                    $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
                }
            }
        }
        $model['cod'] = array();
        $model['cod'] = Customeroperatingdestinations::model()->findAll('id_customer="' . $model['c']->id_customer . '"');
         
        $model['cla'] = Yii::app()->db->createCommand('select cla.*,(select concat(first_name," ",last_name) from {{admin}} a where a.id_admin=cla.id_admin_from) as from_admin,(select concat(first_name," ",last_name) from {{admin}} a where a.id_admin=cla.id_admin_to) as to_admin  from {{customer_lead_assignment}} cla where cla.id_customer="' . $model['c']->id_customer . '" order by cla.date_created desc')->QueryAll(); //Customerleadassignment::model()->findAll('id_customer="'.$model['c']->id_customer.'"');        

        $model['clsh'] = Yii::app()->db->CreateCommand('select clsh.*,concat(a.first_name," ",a.last_name) as admin from {{customer_lead_status_history}} clsh,{{admin}} a where clsh.id_admin=a.id_admin and clsh.id_customer="' . $model['c']->id_customer . '" order by clsh.date_created desc')->QueryAll(); //Customerleadstatushistory::model()->findAll('id_customer="'.$model['c']->id_customer.'"');
        //echo '<pre>';print_r($model['cla']);print_r($model['clsh']);exit;
        $model['field'] = Admin::model()->findAll(array('select' => 'concat(first_name," ",last_name) as first_name,id_admin', 'condition' => 'id_admin_role=11 and status=1'));
        $model['cd'] = Customerdocs::model()->findAll('id_customer="' . $id . '"');
        $model['t']=Truck::model()->findAll('id_customer='.$id);
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
            $custObjs = Customer::model()->findAll($criteria);
            foreach ($custObjs as $custObj) {
                unlink(Library::getMiscUploadPath() . $custObj->profile_image);
            }
            $trucksObj = Truck::model()->findAll($criteria);
            foreach ($trucksObj as $truckObj) {
                $docObjs = Truckdoc::model()->findAll('id_truck="' . $truckObj->id_truck . '"');
                foreach ($docObjs as $docObj) {
                    unlink(Library::getTruckUploadPath() . $docObj->file);
                }
            }

            CActiveRecord::model('Customer')->deleteAll($criteria);
            CActiveRecord::model('Truck')->deleteAll($criteria);
            CActiveRecord::model('Truckrouteprice')->deleteAll($criteria);


            Yii::app()->user->setFlash('success', Yii::t('common', 'message_delete_success'));
        } else {
            Yii::app()->user->setFlash('alert', Yii::t('common', 'message_checkboxValidation_alert'));
            Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
        }
        if (!isset($_GET['ajax']))
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
    }

    public function actionIndex() {
        //echo '<pre>';print_r($this->menu);echo '</pre>';exit;
        //echo date("Y-m-d|h:i:s");
        //echo '<pre>';print_r($_SESSION['EXCEL_ERROR_MESSAGE']);echo '</pre>';
        if (is_uploaded_file($_FILES['import']['tmp_name'])) {
            $fileType=end(explode(".",$_FILES['import']['name']));
            if ($fileType=='xlsx' && (isset( $_FILES['import'] )) && (is_uploaded_file($_FILES['import']['tmp_name']))) {
                $file = $_FILES['import']['tmp_name'];
                $expObj=new Export();
                $return=$expObj->uploadLead($file);        
				Yii::app()->user->setFlash('success', 'Upload Successfull!!');
                $this->redirect($this->createUrl('leads/index'));    
                //$this->refresh();
            }else{
                $error="Invalid fileformat/user";
            }
        }
        
        $this->customerType = Library::getCustomerTypes();
        //echo '<pre>';print_r($this->customerType);exit;
        $model = new Customer('searchCustomerLead');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Customer']))
            $model->attributes = $_GET['Customer'];

        $this->render('index', array(
            'model' => $model,
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
            case 'type':
                //echo '<pre>';print_r($this->customerType);exit;
                $return = $this->customerType[$data->type];
                break;
            case 'id_customer':
                $return = '<a href="#" onclick="fnupload(' . $data->id_customer . ');">Upload</a> | <a href="' . $this->createUrl("cagent/download", array('id' => $data->id_customer)) . '" >Download</a> ';
                break;
        }
        return $return;
    }

}

?>
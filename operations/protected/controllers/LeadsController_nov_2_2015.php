<?php

class LeadsController extends Controller {

    public $customerType;
    public $approved;
    public $id_customer_access_permission;
    public function accessRules() {
        return $this->addActions(array('Approve', 'Updatestatus', 'Updateleadassigment'));
    }

    public function actionUpdateleadassigment() {
        $status=0;
        $id=(int)$_GET['id'];
        $id_admin_to=(int)$_POST['Customerleadassignment']['id_admin_to'];
        if ( ($_POST['Customerleadassignment']['id_admin_to']!="" && $_POST['Customerleadassignment']['name']!="" && $_POST['Customerleadassignment']['mobile']!="" && $_POST['Customerleadassignment']['meeting_date_time']!="") && (Yii::app()->request->getIsAjaxRequest()) && (Yii::app()->request->isPostRequest)) {
            $this->assignDocColl(array('id_customer'=>$id));
            /*$obj = new Customerleadassignment;
            $obj->id_admin_from = Yii::app()->user->id;
            $obj->id_admin_to = $id_admin_to;
            $obj->message = $_POST['Customerleadassignment']['message'];
            $obj->status = 'Document Collection';
            $obj->id_customer = $id;
            $obj->save(false);
            //echo '<pre>';print_r($_POST);EXIT;
            $adminRow=Admin::model()->find('id_admin="'.$id_admin_to.'"');
            $this->addAccessHistory(array('id_customer'=>$id,'message'=>"Document Collection Assigned to ".$adminRow->first_name." ".$adminRow->last_name));
            //$this->addAccessPermission(array('id_admin'=>$_POST['Customerleadassignment']['id_admin_to'],'id_customer'=>$_POST['id']));
            Yii::app()->user->setFlash('success', "Mail sent successully to the user!!");*/
            $status=1;
        }
        echo $status;
        Yii::app()->end();
    }
    
    public function addAccessPermission($input){
        $row=Customeraccesspermission::model()->find(array('select'=>'count(*) as count','condition'=>'id_customer="'.$input['id_customer'].'" and id_admin="'.$input['id_admin'].'"'));
	if(!$row->count){
            $obj=new Customeraccesspermission;
            $obj->id_admin=$input['id_admin'];
            $obj->id_customer = $input['id_customer'];
            $obj->date_created = date('Y-m-d');
            $obj->save(false);
        }
    }
    
    public function assignDocColl($input)
    {
            if($_POST['Customerleadassignment']['id_admin_to']!="" && $_POST['Customerleadassignment']['name']!="" && $_POST['Customerleadassignment']['mobile']!="" && $_POST['Customerleadassignment']['meeting_date_time']!=""){
            $adminRow=Admin::model()->find("id_admin=".$_POST['Customerleadassignment']['id_admin_to']);
            Customerlead::model()->updateAll(array('lead_status'=>'Document Collection'),'id_customer='.$input['id_customer']);               

            $text="Assigned To:".$adminRow->first_name." ".$adminRow->last_name."<br/>Name:".$_POST['Customerleadassignment']['name']."<br/>Mobile:".$_POST['Customerleadassignment']['mobile']."<br/>Meeting Date/Time:".$_POST['Customerleadassignment']['meeting_date_time'];

            $obj=new Customerleadstatushistory;
            $obj->id_admin=Yii::app()->user->id;
            $obj->message = $text.$_POST['Customerleadstatushistory']['message'];
            $obj->status = "Document Collection";
            $obj->id_customer = $input['id_customer'];
            $obj->save(false);
            $this->addAccessPermission(array('id_admin'=>$_POST['Customerleadassignment']['id_admin_to'],'id_customer'=>$input['id_customer']));
            $this->addAccessHistory(array('id_customer'=>$input['id_customer'],'message'=>"Document Collection Assigned to ".$adminRow->first_name." ".$adminRow->last_name));
        }	
    }

    public function actionUpdatestatus() {
        //echo '<pre>';print_r($_POST['Customerleadstatushistory']);exit;
        $status=0;
        $id=(int)$_GET['id'];
        if (Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest && $_POST[Customerleadstatushistory][status]!='') {
            $obj = new Customerleadstatushistory;
            $obj->id_admin = Yii::app()->user->id;
            $obj->message = $_POST['Customerleadstatushistory']['message'];
            $obj->status = $_POST['Customerleadstatushistory']['status'];
            $obj->id_customer = $id;
            $obj->save(false);
            //exit;
            Customerlead::model()->updateAll(array('lead_status'=>$_POST['Customerleadstatushistory']['status']),'id_customer='.$id);
            //echo '<pre>';print_r($_POST);EXIT;
            //exit;
            
            $this->addAccessHistory(array('id_customer'=>$id,'message'=>'Lead Status Updated to '.$_POST['Customerleadstatushistory']['status']));
            Yii::app()->user->setFlash('success', "Updated Successfully!!");
            $approved=$this->approveLead(array('id_customer'=>$id,'status'=>$_POST['Customerleadstatushistory']['status']));
            $this->addAccessPermission(array('id_admin'=>Yii::app()->user->id,'id_customer'=>$id));
            $status=1;
        }
        //echo $status;
        echo CJSON::encode(array('approved'=>$approved,'status'=>$status,'url'=>$this->createUrl('leads/index')));
        Yii::app()->end();
    }
    
    public function approveLead($input){
        
        $approved=0;
        if($input['status']=='Accept Approval'){
            $obj=Customer::model()->find('id_customer="'.$input['id_customer'].'"');
            $tempPassword=Library::randomPassword();
            $password = Admin::hashPassword($tempPassword);
            $idprefix=Library::getIdPrefix(array('type'=>$obj->type,'id'=>$input['id_customer']));
            Customer::model()->updateAll(array('status'=>1,'approved'=>1,'islead'=>0,'idprefix'=>$idprefix),'id_customer="'.$input['id_customer'].'"');
            if($obj->email!=''){
                $data = array('id' => '8', 'replace' => array('%password%' => $password,'%email%' => $obj->email,'%mobile%' => $obj->mobile,'%username%' => $obj->mobile), 'mail' => array("to" => array($obj->email => $obj->fullname)));
                Mail::send($data);
            }
            $approved=1;
            Yii::app()->user->setFlash('success', "Lead Approved Successfully!!");
        }
        return $approved;
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

                $model['c']->save(false);
                if($_POST['Customer']['type']=='T' || $_POST['Customer']['type']=='C'){
                    $this->addTrucks(array('id'=>$model['c']->id_customer));
                    $this->addDriver($model['c']->id_customer);
                }
                $this->setUploadMultipleImages(array('id' => $model['c']->id_customer, 'multiImage' => $_FILES['Customerdocs'],
                    'imageData' => $_POST['Customerdocs']['upload']));
                
                $this->addCOptDest($model['c']->id_customer);
                $model['cl'] = new Customerlead;
                $model['cl']->id_customer = $model['c']->id_customer;
                $model['cl']->lead_source = $_POST['Customer']['lead_source'];
                $model['cl']->lead_status = $_POST['Customerleadstatushistory']['status']==""?'Initiated':$_POST['Customerleadstatushistory']['status'];
                $model['cl']->id_admin_created = Yii::app()->user->id;
                //$model['cl']->id_admin_assigned = $row['id_admin'];
                $model['cl']->save(false);
                $this->updateTruckType(array('id_truck_type'=>$_POST['id_truck_type'],'id'=>$model['c']->id_customer));
                
                if($_POST['Customerleadstatushistory']['status']!=""){
                    $obj=new Customerleadstatushistory;
                    $obj->id_admin=Yii::app()->user->id;
                    $obj->id_customer=$model['c']->id_customer;
                    $obj->message=$_POST['Customerleadstatushistory']['message'];
                    $obj->status=$_POST['Customerleadstatushistory']['status'];
                    $obj->save(false);
                }
                $this->addCOptDest($model['c']->id_customer);
                $this->addAccessHistory(array('id_customer'=>$model['c']->id_customer,'message'=>'Created Lead'));               
                $this->assignDocColl(array('id_customer'=>$model['c']->id_customer));
                $this->addAccessPermission(array('id_admin'=>Yii::app()->user->id,'id_customer'=>$model['c']->id_customer));//exit;
               // echo '<pre>';print_r($model['cvt']);exit;
                /* $data = array('id' => '2', 'replace' => array('%customer_name%' => $_POST['Customer']['fullname']), 'mail' => array("to" => array($_POST['Customer']['email'] => $_POST['Customer']['fullname'])));
                  Mail::send($data);

                  $data = array('id' => '8', 'replace' => array('%customer_name%' => $_POST['Customer']['fullname']), 'mail' => array("to" => array($_POST['Customer']['email'] => $_POST['Customer']['fullname'])));
                  Mail::send($data); */
				//echo '<pre>';print_r($_POST);exit;
                
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
                //exit;
                $this->redirect('index');
            }

        }
        $model['field'] = Admin::model()->findAll(array('select' => 'concat(first_name," ",last_name) as first_name,id_admin', 'condition' => 'id_admin_role=11 and status=1'));
        $model['truckTypes']=Trucktype::model()->findAll('status=1');
	$model['leadStatuses']=Library::getLeadStatuses();
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
            $model->doc_type=$data['imageData'][$k]['doc_type'];
            $model->save(false);
        }
    }
    
    public function setUploadFile($input){
            
            $data['tmp_name']=$_FILES['Truckdoc']['tmp_name']['upload'][$input[k]][$input[field]];
            $data['name']=$_FILES['Truckdoc']['name']['upload'][$input[k]][$input[field]];
            $data['input']['prefix'] = $input[field].'_' . $input[id] . '_';
            $data['input']['path'] = Library::getTruckUploadPath();
            $data['input']['prev_file'] = $input['prev_file'];
        return $vehicle_insurance = Library::fileUpload($data);
    }
    
    public function addTrucks($data){
        Truck::model()->deleteAll('id_customer='.$data['id']);
        foreach($_POST['Truck'] as $k=>$v){
            $model=new Truck;
            
            $input=array("k"=>$k,"id"=>$data[id]);
            
            $input[field]='vehicle_insurance';
            $input[prev_file]=$v['vehicle_insurance_prev'];
            $vi=$this->setUploadFile($input);
            
            $input[field]='fitness_certificate';
            $input[prev_file]=$v['fitness_certificate_prev'];
            $fc=$this->setUploadFile($input);
            
            $input[field]='vehicle_rc';
            $input[prev_file]=$v['vehicle_rc_prev'];
            $rc=$this->setUploadFile($input);
            
            $input[field]='front_pic';
            $input[prev_file]=$v['front_pic_prev'];
            $fp=$this->setUploadFile($input);
            
            $input[field]='back_pic';
            $input[prev_file]=$v['back_pic_prev'];
            $bp=$this->setUploadFile($input);
            
            $input[field]='left_pic';
            $input[prev_file]=$v['left_pic_prev'];
            $lp=$this->setUploadFile($input);
            
            $input[field]='right_pic';
            $input[prev_file]=$v['right_pic_prev'];
            $rp=$this->setUploadFile($input);
            
            $input[field]='top_pic';
            $input[prev_file]=$v['top_pic_prev'];
            $tp=$this->setUploadFile($input);
            
            $model->attributes=$v;
            $model->vehicle_insurance = $vi['file'];
            $model->fitness_certificate= $fc['file'];
            $model->vehicle_rc=$rc['file']; 
            $model->front_pic= $fp['file'];
            $model->back_pic= $bp['file'];
            $model->left_pic = $lp['file'];
            $model->right_pic =$rp['file'];
            $model->top_pic=$tp['file'];
            $model->id_customer=$data['id'];
            $gDetails = Library::getGPDetails($v['source_address']);
            $model->source_address=$v['source_address'];
            $model->source_city=$gDetails['city']==""?$v['source_address']:$gDetails['city'];
            $model->source_state=$gDetails['state'];
            $model->source_lat=$gDetails['lat'];
            $model->source_lng=$gDetails['lng'];
            $model->save(false);
            ///exit;
        }
        //echo '<pre>';print_r($_POST['Truck']);
        //exit;
    }
    
    
		    
    public function addDriver($id){
        //echo '<pre>';print_r($_FILES); print_r($_POST);echo '</pre>';exit;
        Customerdrivercurrent::model()->deleteAll('id_customer="'.$id.'"');
        foreach($_POST[Driver] as $k=>$v){
            if($v['name']==""){ continue; }
            $data['tmp_name']=$_FILES['Driver']['tmp_name'][$k][upload][image];
            $data['name']=$_FILES['Driver']['name'][$k][upload][image];
            $data['input']['prefix'] = 'driver_' . $id . '_';
            $data['input']['path'] = Library::getMiscUploadPath();
            $data['input']['prev_file'] = $v[upload]['prev_image'];
            $upload = Library::fileUpload($data);
            
            $drObj=new Driver;
            $drObj->name=$v[name];
            $drObj->mobile=$v[mobile];
            $drObj->licence_pic=$upload['file'];
            $drObj->date_created=new CDbExpression('NOW()');
            $drObj->save(false);
            $cdc=new Customerdrivercurrent;
            $cdc->id_customer=$id;
            $cdc->id_driver=$drObj->id_driver;
            $cdc->save(false);
            if($v[id_driver]==""){
                $cdh=new Customerdriverhistory;
                $cdh->id_customer=$id;
                $cdh->id_driver=$drObj->id_driver;
                $cdh->save(false);
            }    
        }
        //exit;
    }
    
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (Yii::app()->request->isPostRequest) {
            //echo '<pre>';print_r($_FILES); print_r($_POST);exit;
            
            $this->updateTruckType(array('id_truck_type'=>$_POST['id_truck_type'],'id'=>$id));
			
            $this->addCOptDest($id);
			$model['c']->attributes = $_POST['Customer'];
            //$model['c']->id_default_source_city='';
            if($_POST['Customer']['type']=='T' || $_POST['Customer']['type']=='C'){
                $this->addTrucks(array('id'=>$id));
                $this->addDriver($id);
            }
			//echo '<pre>';print_r($model->attributes);exit;
            
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
                /*if ($model['c']->approved) {
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
                }*/
                if ($model['c']->save(false)) {
                   // echo '<pre>';print_r($model['c']->attributes);exit;
                    Customerlead::model()->updateAll(array('lead_source'=>$_POST['Customer']['lead_source']),'id_customer='.$model['c']->id_customer);
                    $this->addAccessPermission(array('id_admin'=>yii::app()->user->id,'id_customer'=>$id));
                    $this->addAccessHistory(array('id_customer'=>$id,'message'=>'Modified Lead'));
                    Yii::app()->user->setFlash('success', $update_msg);
                    $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
                }
            }
        }
        
        $model['cod'] = array();
        $model['cod'] = Customeroperatingdestinations::model()->findAll('id_customer="' . $model['c']->id_customer . '"');
         
        /*$model['cla'] = Yii::app()->db->createCommand('select cla.*,(select concat(first_name," ",last_name) from {{admin}} a where a.id_admin=cla.id_admin_from) as from_admin,(select concat(first_name," ",last_name) from {{admin}} a where a.id_admin=cla.id_admin_to) as to_admin  from {{customer_lead_assignment}} cla where cla.id_customer="' . $model['c']->id_customer . '" order by cla.date_created desc')->QueryAll(); //Customerleadassignment::model()->findAll('id_customer="'.$model['c']->id_customer.'"');       
        */

        $model['clsh'] = Yii::app()->db->CreateCommand('select clsh.*,concat(a.first_name," ",a.last_name) as admin from {{customer_lead_status_history}} clsh,{{admin}} a where clsh.id_admin=a.id_admin and clsh.id_customer="' . $model['c']->id_customer . '" order by clsh.date_created desc')->QueryAll(); //Customerleadstatushistory::model()->findAll('id_customer="'.$model['c']->id_customer.'"');
        //echo '<pre>';print_r($model['cla']);print_r($model['clsh']);exit;
        $model['field'] = Admin::model()->findAll(array('select' => 'concat(first_name," ",last_name) as first_name,id_admin', 'condition' => 'id_admin_role=11 and status=1'));
        $model['cd'] = Customerdocs::model()->findAll('id_customer="' . $id . '"');
        $model['t']=Truck::model()->findAll('id_customer='.$id);
		$model['truckTypes']=Trucktype::model()->findAll('status=1');
	$model['cvt']=CHtml::listData(Customervechiletypes::model()->findAll('id_customer="'.(int)$_GET['id'].'"'),'id_truck_type','title');
		//echo '<pre>';print_r($model);exit;
        $model['dr']=Driver::getCustomersDriver($id);
        $model['cah']=Customeraccesshistory::getAccessHistory($id);
        //echo 'ss<pre>';print_r($model['cah']);echo '</pre>';exit;
        $model['leadStatuses']=in_array($_SESSION['id_admin_role'],Library::getCLApprovalAccess())?Library::getLeadVerificationStatuses():Library::getLeadStatuses();
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
        $this->approved=Library::getPreApprovalRequests();
        $this->id_customer_access_permission=Admin::model()->getAdminRoleDetails();
        //echo '<pre>';print_r($this->id_admins);echo '</pre>';exit;
        $this->render('index', array(
            'model' => $model,'dataSet'=>$model->searchCustomerLead()
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
            case 'id_customer_access_permission':
                //echo '<pre>';print_r($this->id_customer_access_permission);
                $exp=explode(",",$data->id_customer_access_permission);
                foreach($exp as $id){ 
                    $return.=$br.$this->id_customer_access_permission[$id]['first_name']."-".$this->id_customer_access_permission[$id]['role'];
                    $br="<br/>";
                }
                break;
            case 'type':
                //echo '<pre>';print_r($this->customerType);exit;
                $return = $this->customerType[$data->type];
                break;
            case 'approved':
                $return =$this->approved[$data->approved];
                break;
            case 'id_customer':
                $return = '<a href="#" onclick="fnupload(' . $data->id_customer . ');">Upload</a> | <a href="' . $this->createUrl("cagent/download", array('id' => $data->id_customer)) . '" >Download</a> ';
                break;
        }
        return $return;
    }
    
    public function addAccessHistory($input){
        $obj=new Customeraccesshistory;
        $obj->id_customer=$input['id_customer'];
        $obj->id_admin=Yii::app()->user->id;
        $obj->message=$input['message'];
        $obj->save(false);
    }
}
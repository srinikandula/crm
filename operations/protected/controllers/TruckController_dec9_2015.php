<?php

class TruckController extends Controller {

    public function accessRules()
    {
        return $this->addActions(array('AutocompleteTruck','Approve','Addplan','deleteplan'));
    }
    
    public function actionApprove() {
        $ids = Yii::app()->request->getParam('id');
        //echo '<pre>';print_r($ids);exit;
		
        $approved = 0;
        foreach ($ids as $id) {
            $row = Truck::model()->find('id_truck=' . $id);
            if (!$row->approved) {
                $approved = 1;
                $row->approved = 1;
                $row->save(false);
                $custObj=Customer::model()->find('id_customer='.$row->id_customer);
                if($custObj->enable_sms_email_ads)
                {    
                    $data = array('id' => '3', 'replace' => array('%truck_reg_no%' => $row->truck_reg_no), 'mail' => array("to" => array($custObj->email => $custObj->fullname)));
                    Mail::send($data);
                }
                
            }
        }

        if ($approved) {
            Yii::app()->user->setFlash('success', 'Selected Truck approved successfully!!');
        }
        $this->redirect('index');
    }
    
    public function actionCreate() {
        $model['t'] = new Truck;
        $model['f'] = new Truckdoc;
        $model['c'] = new Customer;
        
        if (Yii::app()->request->isPostRequest) {
            $model['t']->attributes = $_POST['Truck'];
            if($_POST['Truck']['id_customer']){
                $xCust=explode(",",$_POST['Truck']['id_customer']); 
                $custObj=Customer::model()->find('fullname like "'.$xCust[0].'" and mobile="'.$xCust[2].'"');
                if($custObj->id_customer){
                    $model['t']->id_customer=$custObj->id_customer;
                }
            }
            
            if ($model['t']->validate()){
                $model['t']->save();
                
                $this->setUploadMultipleImages(array('id' => $model['t']->id_truck, 'multiImage' => $_FILES['TruckDoc'],
                    'imageData' => $_POST['TruckDoc']['upload']));
            if($custObj->enable_sms_email_ads)
            {    
                $data = array('id' => '10', 'replace' => array('%truck_reg_no%' => $_POST['Truck']['truck_reg_no']), 'mail' => array("to" => array($custObj->email => $custObj->fullname)));
                Mail::send($data);
            }
            Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
            $this->redirect('index');
            }
        }
        $records['customer']=  Customer::model()->findAll('approved=1');
        $this->render('create', array('records'=>$records,'model' => $model));
    }
    
    public function actionAddplan($id) {
        if (Yii::app()->request->getIsAjaxRequest() && isset($_POST)) {
            $model['ctap']->attributes = $_POST['Customertruckattachmentpolicy'];
            $exp = explode("#", $_POST['Customertruckattachmentpolicy']['title']);
            //print_r($exp);exit;
            $ctap = new Customertruckattachmentpolicy();
            $tap = new Truckattachmentpolicy();
            //$t = new Truck();
            $ctap->id_truck = (int) $_GET['id'];
            $ctap->title = $_POST['Customertruckattachmentpolicy']['title'];
            $ctap->min_kms = $_POST['Customertruckattachmentpolicy']['min_kms'];
            $ctap->price_per_km = $_POST['Customertruckattachmentpolicy']['price_per_km'];
            $ctap->flat_rate = $_POST['Customertruckattachmentpolicy']['flat_rate'];
            $ctap->diesel_price_per_km = $_POST['Customertruckattachmentpolicy']['diesel_price_per_km'];
            $ctap->date_start = $_POST['Customertruckattachmentpolicy']['date_start'];
            $ctap->date_end = $_POST['Customertruckattachmentpolicy']['date_end'];
            $ctap->id_truck_attachment_policy = $exp[0];
            $ctap->title = $exp['1'];
            $ctap->save(false);
            Truck::model()->updateAll(array('id_truck_attachment_policy'=>$exp[0],'id_customer_truck_attachment_policy'=>$ctap->id_customer_truck_attachment_policy),'id_truck='.(int)$_GET['id']);
            $content = "<tbody ><tr>
                            <td>" . $ctap->title . "</td>
                            <td>" . $ctap->min_kms . "</td>
                            <td>" . $ctap->price_per_km . "</td>
                            <td>" . $ctap->flat_rate . "</td>
                            <td>" . $ctap->diesel_price_per_km . "</td>
                            <td>" . $ctap->date_start . "</td>
                            <td>" . $ctap->date_end . "</td>";
            echo $content;
            	
        }
    }
    public function actiondeleteplan() {
        if (Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest) {
            Customertruckattachmentpolicy::model()->deleteAll('id_customer_truck_attachment_policy="'.$_POST['id'].'"');
        }
	Yii::app()->end();
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        //echo '<pre>';print_r($model);exit;
        if (isset($_POST['Truck'])) {
            $model['t']->attributes = $_POST['Truck'];
//echo '<pre>';print_r($model['tap']->attributes);exit;
        //echo '<pre>';print_r($model['ctap']->attributes);exit;     
            $data = $_FILES['fitness_certificate'];
            $data['input']['prefix'] = 'fitness_certificate_' . $id . '_';
            $data['input']['path'] = Library::getTruckUploadPath();
            $data['input']['prev_file'] = $_POST['prev_fitness_certificate'];
            $upload = Library::fileUpload($data);
            //echo '<pre>';print_r($upload);exit;
            $model['t']->fitness_certificate = $upload['file'];
            
            $data = $_FILES['vehicle_insurance'];
            $data['input']['prefix'] = 'vehicle_insurance_' . $id . '_';
            $data['input']['path'] = Library::getTruckUploadPath();
            $data['input']['prev_file'] = $_POST['prev_vehicle_insurance'];
            $upload = Library::fileUpload($data);
            $model['t']->vehicle_insurance = $upload['file'];
			
            $data = $_FILES['vehicle_rc'];
            $data['input']['prefix'] = 'vehicle_rc_' . $id . '_';
            $data['input']['path'] = Library::getTruckUploadPath();
            $data['input']['prev_file'] = $_POST['prev_vehicle_rc'];
            $upload = Library::fileUpload($data);
            $model['t']->vehicle_rc = $upload['file'];

            $data = $_FILES['front_pic'];
            $data['input']['prefix'] = 'front_pic_' . $id . '_';
            $data['input']['path'] = Library::getTruckUploadPath();
            $data['input']['prev_file'] = $_POST['prev_front_pic'];
            $upload = Library::fileUpload($data);
            $model['t']->front_pic = $upload['file'];
            //echo '<pre>';print_r($model['t']->front_pic);print_r($upload); echo '</pre>';//exit;
            $data = $_FILES['back_pic'];
            $data['input']['prefix'] = 'back_pic_' . $id . '_';
            $data['input']['path'] = Library::getTruckUploadPath();
            $data['input']['prev_file'] = $_POST['prev_back_pic'];
            $upload = Library::fileUpload($data);
            $model['t']->back_pic = $upload['file'];
			
            $data = $_FILES['right_pic'];
            $data['input']['prefix'] = 'right_pic_' . $id . '_';
            $data['input']['path'] = Library::getTruckUploadPath();
            $data['input']['prev_file'] = $_POST['prev_right_pic'];
            $upload = Library::fileUpload($data);
            $model['t']->right_pic = $upload['file'];
			
            $data = $_FILES['left_pic'];
            $data['input']['prefix'] = 'left_pic_' . $id . '_';
            $data['input']['path'] = Library::getTruckUploadPath();
            $data['input']['prev_file'] = $_POST['prev_left_pic'];
            $upload = Library::fileUpload($data);
            $model['t']->left_pic = $upload['file'];

            $data = $_FILES['top_pic'];
            $data['input']['prefix'] = 'top_pic_' . $id . '_';
            $data['input']['path'] = Library::getTruckUploadPath();
            $data['input']['prev_file'] = $_POST['prev_top_pic'];
            $upload = Library::fileUpload($data);
            $model['t']->top_pic = $upload['file'];
            //echo '<pre>';print_r($model['ctap']);exit;
            $this->setUploadMultipleImages(array('id' => $_GET['id'], 'multiImage' => $_FILES['TruckDoc'],
                    'imageData' => $_POST['TruckDoc']['upload']));
            if ($model['t']->validate()){
        	$model['t']->save();
         			
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
                $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
            }
            
        }
        //$truck=Truckrouteprice::model()->findAll('id_truck='.$id);
        //$file = Truckdoc::model()->findAll('id_truck=' . $id);
        //$this->render('update', array('model' => $model, 'truck' => $truck,'file'=>$file));
        $this->render('update', array('model' => $model));
    }
    
    public function setUploadMultipleImages($data) {
        //echo '<pre>';
        //print_r($_FILES);
        //print_r($_POST);
        //print_r($data);
        //exit;
        $fUploadImage = "";
        if($data['id']){
        Truckdoc::model()->deleteAll('id_truck=' . $data['id']);
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

            $fUploadImage['input']['prefix'] = 'truck_'. '-' . $data['id'] . '_' . $k . '_';
            $fUploadImage['input']['path'] = Library::getTruckUploadPath();
            $fUploadImage['input']['prev_file'] = $data['imageData'][$k]['prev_image'];

            $uploadImage = Library::fileUpload($fUploadImage);
            //echo "file ".$uploadImage['file']."<br/>";
            $model = new Truckdoc;
            $model->id_truck = $data['id'];
            $model->file = $uploadImage['file'];
            $model->save(false);
        }
    //    print_r($images);
    }

    public function actionDelete() {//($id)
        $arrayRowId = is_array(Yii::app()->request->getParam('id')) ? Yii::app()->request->getParam('id') : array(Yii::app()->request->getParam('id'));
        if (sizeof($arrayRowId) > 0) {
            $arrayRowId = $this->findRelated($arrayRowId);

            $criteria = new CDbCriteria;
            $criteria->addInCondition('id_country', $arrayRowId);

            if (CActiveRecord::model('Country')->deleteAll($criteria)) {
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_delete_success'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
            }
        } else {
            Yii::app()->user->setFlash('alert', Yii::t('common', 'message_checkboxValidation_alert'));
            Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
        }

        if (!isset($_GET['ajax']))
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
    }
/*
    public function findRelated($input) {
        $criteria = new CDbCriteria;
        $criteria->condition = 't.id_country IN ( ' . implode(",", $input) . ' )';
        $states = State::model()->findAll($criteria);
        if (sizeof($states) > 0) {
            $items = "";
            $input = array_flip($input);
            foreach ($states as $state):
                $items.=$prefix . strip_tags($state->name);
                $prefix = ",";
                unset($input[$state->id_country]);
            endforeach;
            $input = array_flip($input);

            Yii::app()->user->setFlash('alert', Yii::t('countries', 'warning_country', array('{details}' => $items)));
        }
        return $input;
    }
*/
    public function actionIndex() {
        //echo Yii::app()->user->id;
        //echo '<pre>';print_r($_SESSION);EXIT;
        $model = new Truck('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Truck']))
            $model->attributes = $_GET['Truck'];

        $this->render('index', array('model' => $model,'dataSet'=>$model->search()));
    }

    public function loadModel($id) {
        $model['t'] = Truck::model()->findByPk($id);
        $model['ctap'] = Customertruckattachmentpolicy::model()->findAll('id_truck=' .$id);
        $model['tap'] = Truckattachmentpolicy::model()->find('id_truck_attachment_policy=' . $model['t']->id_truck_attachment_policy);
        $model['f'] = Truckdoc::model()->findAll('id_truck=' . $id);
        $model['c'] = Customer::model()->find('id_customer=' . $model['t']->id_customer);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'country-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'vehicle_insurance': 
                                      $return="";  
                                      if($data->vehicle_insurance==""){
                                          $return.="Insurance,";
                                      }
                                      if($data->fitness_certificate==""){
                                          $return.="Fitness,";
                                      }
                                      if($data->vehicle_rc==""){
                                          $return.="Rc";
                                      }
                                      if($return==""){
                                          $return="--";
                                      }
                break;
            case 'truck_reg_no':
                $return = '<a href="'.$this->createUrl('truckrouteprice/index',array('tid'=>$data->id_truck)).'" target="_blank" >'.$data->truck_reg_no.'</a>';
                break;
			case 'fullname':
			
				if($data->type=='L'){
					$page="loadowner";
				}else if($data->type=='T'){
					$page="truckowner";
				}if($data->type=='G'){
					$page="guest";
				}if($data->type=='C'){
					$page="cagent";
				}

                $return = '<a href="'.$this->createUrl($page.'/update',array('id'=>$data->id_customer)).'" target="_blank" >'.$data->fullname.'</a>';
                break;
        }
        return $return;
    }
    
    public function actionAutocompleteTruck() {
	//exit($_GET['term']);
        $res =array();
        $str="";
	if (isset($_GET['term'])) {
            $qtxt ="SELECT truck_reg_no FROM {{truck}} WHERE approved=1 and booked=0 and status=1 and truck_reg_no  LIKE :truck_reg_no";
            $command =Yii::app()->db->createCommand($qtxt);
            $command->bindValue(":truck_reg_no", '%'.$_GET['term'].'%', PDO::PARAM_STR);
            $res =$command->queryColumn();
	}

	echo CJSON::encode($res);
	Yii::app()->end();
    }
}
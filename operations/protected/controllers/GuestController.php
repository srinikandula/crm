<?php

class GuestController extends Controller {

    public function accessRules() {
        return $this->addActions(array('Movetocl','Approve','Autosuggestmobile'));
    }

    public function ActionAutosuggestmobile(){
        $output=array();
        //if (1)
        if(Yii::app()->request->getIsAjaxRequest())
        {
             //exit($_GET['page']);
            if($_GET['page']=='loadrequest##index'){
            //if(1){
                $qryRows=Yii::app()->db->createCommand('SELECT distinct c.mobile FROM {{truck_load_request}} t  inner join {{customer}} c on t.id_customer=c.id_customer left join {{truck_type}} tt on t.id_truck_type=tt.id_truck_type left join {{goods_type}} gt on gt.id_goods_type=t.id_goods_type WHERE c.mobile like "%'.(int)$_GET['term'].'%"  and t.id_admin_created="'.Yii::app()->user->id.'" ORDER BY id_truck_load_request')->queryAll();
                foreach($qryRows as $row){
                    $output[]=$row['mobile'];
                }
            }
            echo CJSON::encode($output);
            Yii::app()->end();
        }
    }
    
    public function actionApprove() {
        $ids = Yii::app()->request->getParam('id');
        $approved = 0;
        foreach ($ids as $id) {
            $row = Customer::model()->find('id_customer=' . $id);
            if (!$row->approved) {
                $data['from'] =Yii::app()->config->getData('CONFIG_STORE_SUPPORT_EMAIL_ADDRESS');
                $data['to'] =$row->email ;
                $data['subject'] ='EasyGaadi.com Account Approved!!' ;
                $data['message'] ='Congratulations,Your account got verified and approved by our team!!' ;
                Library::sendMail($data);
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
    
        public function actionMovetocl() {
        $ids = Yii::app()->request->getParam('id');
        $approved = 0;
        foreach ($ids as $id) {
            $approved = 1;
            $id_admin=Admin::model()->assignToInboundUser();
            Customer::model()->updateAll(array('islead'=>1),'id_customer="'.$id.'"');
            $obj=new Customeraccesspermission;
            $obj->id_customer=$id;
            $obj->id_admin=$id_admin;
            $obj->date_created=date('Y-m-d');
            $obj->save(false);        
        }

        if ($approved) {
            Yii::app()->user->setFlash('success', 'Selected Guest moved to customer leads successfully!!');
        }
        $this->redirect('index');
    }

    public function actionCreate() {
        $model['c'] = new Customer;
        if (Yii::app()->request->isPostRequest) {
            $model['c']->attributes = $_POST['Customer'];
            $model['c']->type='G';
            $data = $_FILES['image'];
            $data['input']['prefix'] = 'profile_' . $id . '_';
            $data['input']['path'] = Library::getMiscUploadPath();
            $data['input']['prev_file'] = $_POST['prev_file'];
            $upload = Library::fileUpload($data);
            $model['c']->profile_image = $upload['file'];

            if ($model['c']->validate()) {
                $model['c']->date_created = new CDbExpression('NOW()');
                $model['c']->password = Admin::hashPassword($_POST['Customer']['password']);
                $model['c']->save(false);

                Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
                $this->redirect('index');
            }
        }
        $this->render('create', array('model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (Yii::app()->request->isPostRequest) {
            //echo '<pre>';print_r($_REQUEST);exit;
            
            $model['c']->attributes=$_POST['Customer'];
            //$model['c']->id_default_source_city='';
            
            $data = $_FILES['image'];
            $data['input']['prefix'] = 'profile_' . $id . '_';
            $data['input']['path'] = Library::getMiscUploadPath();

            $data['input']['prev_file'] = $_POST['prev_file'];
            $upload = Library::fileUpload($data);
            $model['c']->profile_image = $upload['file'];
            //echo '<pre>';print_r($_FILES);print_r($upload);
            //exit;
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
        $this->render('update', array('model' => $model,));
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
                unlink(Library::getTruckUploadPath() . $trucksObjRow->driver_driving_licence);
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
        $model = new Customer('searchGuest');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Customer']))
            $model->attributes = $_GET['Customer'];

        $this->render('index', array(
            'model' => $model,'dataSet'=>$model->searchGuest()
        ));
    }

    public function loadModel($id) {
        $model['c'] = Customer::model()->find(array("condition" => "id_customer=" . $id));

        return $model;
    }
    
    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'no_of_loads':
                $return = '<a href="'.$this->createUrl('load/index',array('cid'=>$data->id_customer)).'" target="_blank" >'.$data->no_of_loads.'</a>';
                break;
            
            case 'no_of_trucks':
                $return = '<a href="'.$this->createUrl('truck/index',array('cid'=>$data->id_customer)).'" target="_blank" >'.$data->no_of_trucks.'</a>';
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

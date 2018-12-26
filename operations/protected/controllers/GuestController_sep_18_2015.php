<?php

class GuestController extends Controller {

    public function accessRules() {
        return $this->addActions(array('Approve'));
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
            CActiveRecord::model('Customer')->deleteAll($criteria);
            CActiveRecord::model('Loadtruckrequest')->deleteAll($criteria);

            Yii::app()->user->setFlash('success', Yii::t('common', 'message_delete_success'));
        } else {
            Yii::app()->user->setFlash('alert', Yii::t('common', 'message_checkboxValidation_alert'));
            Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
        }
        if (!isset($_GET['ajax']))
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
    }

    public function actionIndex() {
        $model = new Customer('searchGuest');
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

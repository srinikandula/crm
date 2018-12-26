<?php

class SuppliersController extends Controller {

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
                $data = array('id' => '4', 'replace' => array('%customer_name%' => $row->firstname . " " . $row->lastname), 'mail' => array("to" => array($row->email => $row->firstname . " " . $row->lastname)));
                //echo '<pre>';print_r($data);exit;
                Mail::send($data);
                $approved = 1;
                $row->approved = 1;
                $row->save(false);
            }
        }

        if ($approved) {
            Yii::app()->user->setFlash('success', 'Selected customers approved successfully!!');
        }
        $this->redirect('index');
    }

    public function actionCreate() {
        $model['c'] = new Customer;
        if (Yii::app()->request->isPostRequest) {
            $model['c']->attributes = $_POST['Customer'];
            $model['c']->type="S";
            if ($model['c']->validate()) {
                $model['c']->date_created = new CDbExpression('NOW()');
                $model['c']->password = Customer::hashPassword($_POST['Customer']['password']);
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
            $model['c']->attributes=$_POST['Customer'];
            $model['c']->firstname = $_POST['Customer']['firstname'];
            $model['c']->email = $_POST['Customer']['email'];
            $model['c']->telephone = $_POST['Customer']['telephone'];
            $model['c']->status = $_POST['Customer']['status'];
            $model['c']->type="S";
            if (!empty($_POST['Customer']['password'])) {
                $model['c']->password = Admin::hashPassword($_POST['Customer']['password']);
            }
            
            if ($model['c']->validate()) {
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
            CActiveRecord::model('Customer')->deleteAll($criteria);

            Yii::app()->user->setFlash('success', Yii::t('common', 'message_delete_success'));
        } else {
            Yii::app()->user->setFlash('alert', Yii::t('common', 'message_checkboxValidation_alert'));
            Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
        }
        if (!isset($_GET['ajax']))
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
    }

    public function actionIndex() {
        //exit("here");
        $model = new Customer('searchSupplier');
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
}

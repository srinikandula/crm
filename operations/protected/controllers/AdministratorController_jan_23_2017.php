<?php

class AdministratorController extends Controller {

    public function actionCreate() {
        $model = new Admin;
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['Admin'];

            //$model->addError('email','Email Should be Unique!!');
            if ($model->validate()) {
                $model->password = Admin::hashPassword($_POST['Admin']['password']);
                if ($model->save(false))
                    Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
                $this->redirect('index');
            }
        }
        $this->render('create', array('model' => $model));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (Yii::app()->request->isPostRequest) {
            $model->first_name = $_POST['Admin']['first_name'];
            $model->state = $_POST['Admin']['state'];
            $model->city = $_POST['Admin']['city'];
            $model->last_name = $_POST['Admin']['last_name'];
            //$model->email=$_POST['Admin']['email'];
            $model->phone = $_POST['Admin']['phone'];
            if($_SESSION['id_admin_role']==1){ //only global admin
                $model->status = $_POST['Admin']['status'];
                $model->id_admin_role = $_POST['Admin']['id_admin_role'];
            }
            //$model->attributes=$_POST['Admin'];
            if (!empty($_POST['Admin']['password'])) {
                $model->password = Admin::hashPassword($_POST['Admin']['password']);
            }
            if ($model->save(false))
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete() {//($id)
        $arrayRowId = is_array(Yii::app()->request->getParam('id')) ? Yii::app()->request->getParam('id') : array(Yii::app()->request->getParam('id'));
        if (sizeof($arrayRowId) > 0) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id_admin', $arrayRowId);

            if (CActiveRecord::model('Admin')->deleteAll($criteria)) {
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

    public function actionIndex() {
        $model = new Admin('search');
        
        if (isset($_GET['Admin'])){
            $model->unsetAttributes();  // clear any default values
            $model->attributes = $_GET['Admin'];
        }   
        $this->render('index', array('model' => $model,'dataSet'=>$model->search()));
    }

    public function loadModel($id) {
        $model = Admin::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'admin-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

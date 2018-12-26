<?php

class OrderstatusController extends Controller {

    public function actionCreate() {
        $model = new Orderstatus;
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['Orderstatus'];
            if ($model->save())
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
            $this->redirect('index');
        }
        //echo '<pre>';print_r($model);exit;
        $this->render('create', array('model' => $model));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        //echo '<pre>';print_r($model);exit;
        if (isset($_POST['Orderstatus'])) {
            $model->attributes = $_POST['Orderstatus'];
            if ($model->save())
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete() {//($id)
        //$_GET['id']
        $arrayRowId = is_array(Yii::app()->request->getParam('id')) ? Yii::app()->request->getParam('id') : array(Yii::app()->request->getParam('id'));
        if (sizeof($arrayRowId) > 0) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id_order_status', $arrayRowId);

            if (CActiveRecord::model('Orderstatus')->deleteAll($criteria)) {
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


        $model = new Orderstatus('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Orderstatus']))
            $model->attributes = $_GET['Orderstatus'];

        $this->render('index', array('model' => $model));
    }

    public function loadModel($id) {
        $model = Orderstatus::model()->findByPk($id);
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

}

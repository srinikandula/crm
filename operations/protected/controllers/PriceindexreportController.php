<?php

class PriceindexreportController extends Controller {

    public function actionIndex() {
        $model=New Customer();
        $model->unsetAttributes();  // clear any default values
        $getRequest=$_GET['Customer'];
        if (isset($getRequest))
            $model->attributes = $getRequest;
        $sqlDataProvider=Customer::model()->getCustomersByQuoteRank($getRequest);
        
        //$model=$arrayDataProvider;
        $this->render('index', array('dataProvider' => $sqlDataProvider,'model'=>$model));
    }

    public function loadModel($id) {
        $model = Loadtype::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
    
    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            
            case 'rating':
                //echo '<pre>';print_r($this->customerType);exit;
                $return = '<img title="'.$data['rating'].'" alt="'.$data['rating'].'" src="'.Yii::app()->params['config']['admin_url'].'img/stars-'.$data['rating'].'.png">';
                break;
        }
        return $return;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'country-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
<?php

class OrderpaymentpendingreportController extends Controller {
    
    public function accessRules() {
        return $this->addActions(array('Download'));
    }
    
    public function actionDownload(){
        $expObj=new Export();
        $return=$expObj->downloadOrderReport();
        exit;
    }
    
    public function actionIndex() {
        $model=New Order();
        $model->unsetAttributes();  // clear any default values
        $getRequest=$_GET['Order'];
        //echo '<pre>';print_r($getRequest); echo '</pre>';
        if (isset($getRequest)){
            $model->attributes = $getRequest;
            //echo '<pre>';print_r($model->attributes); echo '</pre>';
        }
        $sqlDataProvider=Order::model()->getOrderpaymentpendingreport($getRequest);
        
        //$model=$arrayDataProvider;
        $this->render('index', array('dataProvider' => $sqlDataProvider,'model'=>$model));
    }

    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            
            
            case 'id_order':
                $return = "<a href='".$this->createUrl('order/update',array('id'=>$data['id_order'],'backurl'=>base64_encode($this->createUrl('order/index'))))."' target='_blank'>".$data['id_order']."</a>";
                break;
            case 'billing':
                //echo '<pre>';print_r($this->customerType);exit;
                $return = $data['billing']."/".$data['transaction'];
                break;
            case 'transaction':
                //echo '<pre>';print_r($this->customerType);exit;
                $return = $data['billing']-$data['transaction'];
                break;
            case 'tobilling':
                //echo '<pre>';print_r($this->customerType);exit;
                $return = $data['tobilling']."/".$data['totransaction'];
                break;
            case 'totransaction':
                //echo '<pre>';print_r($this->customerType);exit;
                $return = $data['tobilling']-$data['totransaction'];
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
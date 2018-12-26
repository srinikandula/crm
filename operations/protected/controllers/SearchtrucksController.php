<?php

class SearchtrucksController extends Controller {
            
    public function actionIndex() {
        $model = new Truckrouteprice('searchTruckBooking');
        $model->unsetAttributes();
        //echo '<pre>';print_r($model);exit;
        if (isset($_GET['Truckrouteprice']))
            $model->attributes = $_GET['Truckrouteprice'];
            
        $this->render('index', array(
            'model' => $model,
        ));
        //$this->render('create', array('model' => $model));
    }

            
    public function loadModel($id) {
        $model['0'] = Order::model()->find(array("condition" => "id_order=" . $id));
        //$model['oh'] = Orderhistory::model()->find(array("condition" => "id_order=" . $id));
        $model['oh'] = new Orderhistory();
        return $model;
    }

    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'no_of_trucks':
                $return = '<a href="' . $this->createUrl('truck/index', array('cid' => $data->id_customer)) . '" target="_blank" >' . $data->no_of_trucks . '</a>';
                break;
        }
        return $return;
    }
    
    public function getPrice($data){
        $in="";
                //echo '<pre>';print_r($data);echo '</pre>';
                    if($data->customer_commission_type!=""){
                        $in="c";
                        $comm=$data->customer_commission;
                        $comm_type=$data->customer_commission_type;
                    }else if($data->truck_commission_type!=""){
                        $in="t";
                        $comm=$data->truck_commission;
                        $comm_type=$data->truck_commission_type;
                    }else if($data->truck_route_commission_type!=""){
                        $in="tr";
                        $comm=$data->truck_route_commission;
                        $comm_type=$data->truck_route_commission_type;
                    }else{
                        $in="d";
                        $comm=Yii::app()->config->getData('CONFIG_WEBSITE_GLOBAL_COMMISSION');
                        $comm_type=Yii::app()->config->getData('CONFIG_WEBSITE_GLOBAL_COMMISSION_TYPE');
                    }
                    if($comm_type=='P'){
                        $return=$data->price+($data->price*$comm/100);
                    }else{
                        $return=$data->price+$comm;
                    }
                    return $return;
    }
    
    protected function gridSearch($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'price':
                
                    $return =$this->getPrice($data);
                    //$return.=$in;
                break;
             
            case 'status':
                //$return="helo";
                $return = '<input type="radio" name="select" id="select" onclick="fnordercustomer('.$data->id_truck_route_price.','.$this->getPrice($data).');" value="'.$data->id_truck_route_price.'">';
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

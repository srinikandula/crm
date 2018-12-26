<?php

class TruckroutepriceController extends Controller {

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
        $model['trp'] = new Truckrouteprice;
        if (Yii::app()->request->isPostRequest) {
             
            $model['trp']->attributes=$_POST['Truckrouteprice'];
            $expSource=explode(', ',$_POST['Truckrouteprice']['source_address']);
            $expDestination=explode(', ',$_POST['Truckrouteprice']['destination_address']);
            $expSourceRev=  array_reverse($expSource);
            $expDestinationRev=  array_reverse($expDestination);
            $gdetailsSource=Library::getGooglePlaceDetails($_POST['Truckrouteprice']['source_address']);
            $gdetailsDestination=Library::getGooglePlaceDetails($_POST['Truckrouteprice']['destination_address']);
            if($expSourceRev[0]=='India'){
                $model['trp']->source_state=trim($expSourceRev[1]);
                $model['trp']->source_city=trim($expSourceRev[2]);
                $model['trp']->source_lat=trim($gdetailsSource['results'][0]['geometry']['location']['lat']);
                $model['trp']->source_lng=trim($gdetailsSource['results'][0]['geometry']['location']['lng']);
                
            }
            
            if($expDestinationRev[0]=='India'){
                $model['trp']->destination_state=trim($expDestinationRev[1]);
                $model['trp']->destination_city=trim($expDestinationRev[2]);
                $model['trp']->destination_lat=trim($gdetailsDestination['results'][0]['geometry']['location']['lat']);
                $model['trp']->destination_lng=trim($gdetailsDestination['results'][0]['geometry']['location']['lng']);
            }
            
            if($_POST['Truckrouteprice']['id_truck']){
            //$xCust=explode(",",$_POST['Truckrouteprice']['id_truck']); 
                $custObj=Truck::model()->find('truck_reg_no like "'.$_POST['Truckrouteprice']['id_truck'].'"');
                //exit("value of ".$custObj->id_customer);
                if($custObj->id_truck){
                    $model['trp']->id_truck=$custObj->id_truck;
                    $model['trp']->id_customer=$custObj->id_customer;
                }
            }
            $model['trp']->price = $_POST['Truckrouteprice']['price'];
            $model['trp']->status = $_POST['Truckrouteprice']['status'];
            $model['trp']->id_load_type = $_POST['Truckrouteprice']['id_load_type'];
            $model['trp']->id_goods_type = $_POST['Truckrouteprice']['id_goods_type'];
            $model['trp']->date_created=new CDbExpression('NOW()');
            if ($model['trp']->validate()) {
                if ($model['trp']->save(false)) {
                    Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
                    $this->redirect('index');
                }
            }
        }
        $records['truck']=  Truck::model()->findAll('approved=1 order by truck_reg_no asc');
        $this->render('create', array('records'=>$records,'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (Yii::app()->request->isPostRequest) {
            $model['trp']->attributes=$_POST['Truckrouteprice'];
            $expSource=explode(', ',$_POST['Truckrouteprice']['source_address']);
            $expDestination=explode(', ',$_POST['Truckrouteprice']['destination_address']);
            $expSourceRev=  array_reverse($expSource);
            $expDestinationRev=  array_reverse($expDestination);
            $gdetailsSource=Library::getGooglePlaceDetails($_POST['Truckrouteprice']['source_address']);
            $gdetailsDestination=Library::getGooglePlaceDetails($_POST['Truckrouteprice']['destination_address']);
            //echo "value of ".$gdetails['results'][0]['geometry']['location']['lat'];
            //echo '<pre>';print_r($expSource);print_r($expDestination);print_r($expSourceRev);print_r($expDestinationRev);print_r($_POST);EXIT;
            if($expSourceRev[0]=='India'){
                $model['trp']->source_state=trim($expSourceRev[1]);
                $model['trp']->source_city=trim($expSourceRev[2]);
                $model['trp']->source_lat=trim($gdetailsSource['results'][0]['geometry']['location']['lat']);
                $model['trp']->source_lng=trim($gdetailsSource['results'][0]['geometry']['location']['lng']);
                
            }
            
            if($expDestinationRev[0]=='India'){
                $model['trp']->destination_state=trim($expDestinationRev[1]);
                $model['trp']->destination_city=trim($expDestinationRev[2]);
                $model['trp']->destination_lat=trim($gdetailsDestination['results'][0]['geometry']['location']['lat']);
                $model['trp']->destination_lng=trim($gdetailsDestination['results'][0]['geometry']['location']['lng']);
            }
            //$expSource=explode('-',$_POST['Truckrouteprice']['source_city']);
            //$expDestination=explode('-',$_POST['Truckrouteprice']['destination_city']);
            //$sourceObj=City::model()->find('lower(trim(city))="'.trim($expSource['0']).'" and lower(trim(state))="'.trim($expSource['1']).'"');
            //$destinationObj=City::model()->find('lower(trim(city))="'.trim($expDestination['0']).'" and lower(trim(state))="'.trim($expDestination['1']).'"');
            //$model['trp']->id_source = $sourceObj->id_city;
            //$model['trp']->id_destination = $destinationObj->id_city;
            //$model['trp']->source_city = trim($expSource['0']);
            //$model['trp']->source_state = trim($expSource['1']);
            //$model['trp']->destination_city = trim($expDestination['0']);
            //$model['trp']->destination_state = trim($expDestination['1']);
            $model['trp']->price = $_POST['Truckrouteprice']['price'];
            $model['trp']->status = $_POST['Truckrouteprice']['status'];
            $model['trp']->id_load_type = $_POST['Truckrouteprice']['id_load_type'];
            $model['trp']->id_goods_type = $_POST['Truckrouteprice']['id_goods_type'];
            
            if ($model['trp']->validate()) {
                if ($model['trp']->save(false)) {
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
            $criteria->addInCondition('id_truck_route_price', $arrayRowId);
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
        $model = new Truckrouteprice('searchPrice');
        $model->unsetAttributes();  // clear any default values
        //echo "value of ".$_GET['tid'];
        //exit;
        if (isset($_GET['Truckrouteprice']))
            $model->attributes = $_GET['Truckrouteprice'];
            $this->render('index', array(
            'model' => $model,'dataSet'=>$model->searchPrice()
        ));
    }

    public function loadModel($id) {
        $model['trp'] = Truckrouteprice::model()->find(array("condition" => "id_truck_route_price=" . $id));
        $model['t'] = Truck::model()->find(array("condition" => "id_truck=" . $model['trp']->id_truck));
        $model['c'] = Customer::model()->find(array("condition" => "id_customer=" . $model['t']->id_customer));
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'manufacturer-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
}

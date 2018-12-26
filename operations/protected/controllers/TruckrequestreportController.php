<?php

class TruckrequestreportController extends Controller {
    public $getPrefix;
    public function actionUpdate() {
        //exit;
        $model = new Loadtruckrequest('getTruckRequestDetails');
        $this->renderPartial('_preview_block', array('model' =>$model->getTruckRequestDetails()));
    }
    
    public function actionIndex() {
        //ECHO '<pre>';print_r($_GET);ECHO '</pre>';
        $this->getPrefix=sizeof($_GET)>0?"&":"?";
        //echo Yii::app()->request->requestUri;exit;
        $model = new Loadtruckrequest('truckRequestReport');
        //exit($block['booked_requests']->id_load_truck_request." ".$block['booked_requests']->title);
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Loadtruckrequest']))
            $model->attributes = $_GET['Loadtruckrequest'];
        //$block['canceled_requests']=$model->getCanceledRequests();
        //$block['booked_requests']=$model->getBookedRequests();
        $block['booked_requests']=$model->getTruckRequestBlock(array('type'=>'booked_requests'));
        
        $block['canceled_requests']=$model->getTruckRequestBlock(array('type'=>'canceled_requests'));
        //exit;
        //$block['requests_from_portal']=$model->getTruckRequestBlock(array('type'=>'requests_from_portal'));
        //$block['requests_from_crm']=$model->getTruckRequestBlock(array('type'=>'requests_from_crm'));
        $model->group_by=$model->group_by==""?'week':$model->group_by;
        //exit("here");
		$this->render('index', array('block'=>$block,'model' => $model, 'dataSet' => $model->truckrequestreport()));
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'country-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'id_load_truck_request':
                
                $return = '<a  href="'.Yii::app()->request->requestUri.$this->getPrefix.'data='.base64_encode($data->title).'" >' . $data->id_load_truck_request . '</a>';
                break;
        }
        return $return;
    }
}
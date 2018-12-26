<?php
class LoadrequestreportController extends Controller {
    public $getPrefix;
    public function actionUpdate() {
        //exit;
        $model = new Truckloadrequest('getLoadRequestDetails');
        $this->renderPartial('_preview_block', array('model' =>$model->getLoadRequestDetails()));
    }
    
    public function actionIndex() {
        $this->getPrefix=sizeof($_GET)>0?"&":"?";
        $model = new Truckloadrequest('loadRequestReport');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Truckloadrequest']))
            $model->attributes = $_GET['Truckloadrequest'];
        //$block['booked_requests']=$model->getLoadRequestBlock(array('type'=>'booked_requests'));
        $block['canceled_requests']=$model->getLoadRequestBlock(array('type'=>'canceled_requests'));
        $model->group_by=$model->group_by==""?'week':$model->group_by;
        $this->render('index', array('block'=>$block,'model' => $model, 'dataSet' => $model->loadrequestreport()));
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'country-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'id_truck_load_request':
                
                $return = '<a  href="'.Yii::app()->request->requestUri.$this->getPrefix.'data='.base64_encode($data->title).'" >' . $data->id_truck_load_request . '</a>';
                break;
        }
        return $return;
    }
}
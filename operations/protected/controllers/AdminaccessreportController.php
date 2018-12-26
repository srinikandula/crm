<?php

class AdminaccessreportController extends Controller {

    public function actionIndex() {
        /*$model=New AdminLogHistory();
        $model->unsetAttributes();  // clear any default values
        $getRequest=$_GET['AdminLogHistory'];
        if (isset($getRequest))
            $model->attributes = $getRequest;
            //echo '<pre>';print_r($model->attributes);exit;
        $sqlDataProvider=AdminLogHistory::model()->getLogHistoryReport();
        
        //$model=$arrayDataProvider;
        $this->render('index', array('dataProvider' => $sqlDataProvider,'model'=>$model));*/
        
        $model = new AdminLogHistory('getLogHistoryReport');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['AdminLogHistory']))
            $model->attributes = $_GET['AdminLogHistory'];

        $this->render('index', array(
            'model' => $model,'dataProvider'=>$model->getLogHistoryReport()
        ));
    }

    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            
            case 'first_name':
                $return = '<a target="_blank" class="grid_link" href="'.$this->CreateUrl('adminloghistory/index',array('AdminLogHistory[start_date]'=>date("Y-m-d", strtotime($data->start_date)),'AdminLogHistory[end_date]'=>date("Y-m-d", strtotime($data->end_date)),'AdminLogHistory[first_name]'=>$data->first_name,'AdminLogHistory[last_name]'=>$data->last_name)).'">'.$data->first_name.'</a>';
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
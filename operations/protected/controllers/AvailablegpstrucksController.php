<?php

class AvailablegpstrucksController extends Controller {

    public function actionIndex() {
       $model=New Gpstrucklocation('search');
       $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Gpstrucklocation']))
                    $model->attributes=$_GET['Gpstrucklocation'];
        $this->render('index',array('model'=>$model,'dataProvider'=>$model->search()));
    }

    
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'country-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

	public function actionUpdate($id)
	{
            //echo '<pre>';print_r($_POST);exit;
            $aid=(int)$_GET['aid'];
            $id=(int)$_GET['id'];
            $cid=(int)$_GET['cid'];
            $json=array();
            $json['status']=0;
            //if (Yii::app()->request->getIsAjaxRequest() && $id ) {
            if ($id ) {
                Gpstrucklocation::model()->updateAll(array('add_points'=>$aid),'id_gps_truck_location='.$id);
                if($aid){
                    Yii::app()->db->createCommand("update eg_customer set loyality_points=loyality_points+10 where id_customer='".$cid."'")->query();
                }else{
                    Yii::app()->db->createCommand("update eg_customer set loyality_points=loyality_points-10 where id_customer='".$cid."'")->query();
                }
                $json['status']=1;
            }
            echo CJSON::encode($json);
            Yii::app()->end();
	}

	protected function grid($data, $row, $dataColumn) {
            switch ($dataColumn->name) {
                case 'add_points';
                    $select='<select name="select" onchange="fnchange(this,'.$data['id_gps_truck_location'].',\''.$data['id_customer'].'\')">';
                    $one=$data['add_points']==1?"selected":"";
                    $zero=$data['add_points']==0?"selected":"";
                    $select.='<option '.$one.' value="1">Valid</option><option '.$zero.' value="0">InValid</option>';
                    $select.='</select>';
                    $return=$select;//$this->admins[$data['id_admin_assigned']]['name'];
                    break;
            }
            return $return;
    }
}
<?php

class GpsneedloadController extends Controller
{

    public function actionDelete()//($id)
    {
        $arrayRowId=  is_array(Yii::app()->request->getParam('id'))?Yii::app()->request->getParam('id'):array(Yii::app()->request->getParam('id'));
        if(sizeof($arrayRowId)>0)
        {
            $criteria=new CDbCriteria;
            $criteria->addInCondition('id', $arrayRowId);

            if(CActiveRecord::model('GpsDeviceLoyalityPoints')->deleteAll($criteria))
            {
                Yii::app()->user->setFlash('success',Yii::t('common','message_delete_success'));
            }else
            {
                Yii::app()->user->setFlash('error',Yii::t('common','message_delete_fail'));
            }
        }else
        {
            Yii::app()->user->setFlash('alert',Yii::t('common','message_checkboxValidation_alert'));
            Yii::app()->user->setFlash('error',Yii::t('common','message_delete_fail'));
        }

        if(!isset($_GET['ajax']))
            $this->redirect(base64_decode (Yii::app()->request->getParam('backurl')));
    }
	
        public function actionIndex()
	{
            $model=new GpsDeviceLoyalityPoints('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['GpsDeviceLoyalityPoints']))
                    $model->attributes=$_GET['GpsDeviceLoyalityPoints'];
            //echo '<pre>';print_r($model);exit;
            $this->render('index',array('model'=>$model,'dataSet'=>$model->search()));
	}

	public function loadModel($id)
	{
		$model=GpsDeviceLoyalityPoints::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='country-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionUpdate($id)
	{
            //echo '<pre>';print_r($_POST);exit;
            $aid=(int)$_GET['aid'];
            $id=(int)$_GET['id'];
            $accountid=$_GET['accountid'];
            $json=array();
            $json['status']=0;
            //if (Yii::app()->request->getIsAjaxRequest() && $id ) {
            if ($id ) {
                GpsDeviceLoyalityPoints::model()->updateAll(array('status'=>$aid),'id='.$id);
                if($aid){
                    Yii::app()->db->createCommand("update eg_customer set loyality_points=loyality_points+10 where gps_account_id='".$accountid."'")->query();
                }else{
                    Yii::app()->db->createCommand("update eg_customer set loyality_points=loyality_points-10 where gps_account_id='".$accountid."'")->query();
                }
                $json['status']=1;
            }
            echo CJSON::encode($json);
            Yii::app()->end();
	}
        
        protected function grid($data, $row, $dataColumn) {
            switch ($dataColumn->name) {
                case 'status';
                    $select='<select name="select" onchange="fnchange(this,'.$data['id'].',\''.$data[accountID].'\')">';
                    $one=$data['status']==1?"selected":"";
                    $zero=$data['status']==0?"selected":"";
                    $select.='<option '.$one.' value="1">Valid</option><option '.$zero.' value="0">InValid</option>';
                    $select.='</select>';
                    $return=$select;//$this->admins[$data['id_admin_assigned']]['name'];
                    break;
            }
            return $return;
        }
}
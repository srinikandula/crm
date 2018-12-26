<?php

class GpsdevicesController extends Controller
{
    public $vehicleType=array('TK'=>'Truck','NTK'=>'Non Truck','TR'=>'Transporter');
        public function actionCreate()
	{
		$model=new GpsDevice;
		if(Yii::app()->request->isPostRequest)
		{
			$model->attributes=$_POST['GpsDevice'];
                        $model->vehicleID =$_POST['GpsDevice']['deviceID'];
                        $model->uniqueID =$_POST['GpsDevice']['deviceID'];
                        $model->displayName =$_POST['GpsDevice']['deviceID'];
                        $model->description =$_POST['GpsDevice']['deviceID'];
                        $model->statusCodeState = 61717;
                        $model->ignitionIndex = 61717;
                        $model->supportedEncodings  =7;
                        $model->isActive  =1;
                        $model->creationTime =time();
                        $model->lastOdometerKM=0;
                        $model->lastDistanceKM=0;
                        $model->lastValidSpeedKPH=0;
                        $model->vehicleModel=$_POST['GpsDevice']['truckTypeId'];
                        $model->deviceID = strtolower($model->deviceID);
                        $tt = $model->vehicleModel;
                        $truck=Trucktype::model()->findAll(array('select'=>'id_truck_type, title','condition'=>'id_truck_type='.$tt));     
                        $model->vehicleModel = $truck[0]['title'];
                        //echo '<pre>';print_r($_POST['GpsDevice']);exit;
                        if($model->vehicleType == "NTK"){
                            $model->vehicleModel=$_POST['GpsDevice']['vehicleModel'];
                           }
                        
                        //echo '<pre>';print_r($model->attributes);exit;
			if($model->validate()){
                            $model->save(false);
                            Yii::app()->user->setFlash('success',Yii::t('common','message_create_success'));
                            $this->redirect('index');
                        }
		}
		$this->render('create',array('model'=>$model));
	}

	public function actionUpdate()
	{
		//$model=$this->loadModel($id);
                $pk = $_GET['ids']['deviceID'];
                //echo $aa;exit;
                $model = GpsDevice::model()->find("deviceID='$pk'");
		if(isset($_POST['GpsDevice']))
		{
			$model->attributes=$_POST['GpsDevice'];
                        $model->vehicleID =$_POST['GpsDevice']['deviceID'];
                        $model->uniqueID =$_POST['GpsDevice']['deviceID'];
                        $model->displayName =$_POST['GpsDevice']['deviceID'];
                        $model->description =$_POST['GpsDevice']['deviceID'];
                        $model->vehicleModel=$_POST['GpsDevice']['truckTypeId'];
                        $tt = $model->vehicleModel;
                        $truck=Trucktype::model()->findAll(array('select'=>'id_truck_type, title','condition'=>'id_truck_type='.$tt));     
                        $model->vehicleModel = $truck[0]['title'];
                        //echo '<pre>';print_r($_POST['GpsDevice']);exit;
                        if($model->vehicleType == "NTK"){
                            $model->vehicleModel=$_POST['GpsDevice']['vehicleModel'];
                           }
                           //$model->isActive =$_POST['GpsDevice']['isActive'];
                        //echo '<pre>';print_r($model->isActive);exit;
                    if($model->validate())
                    {
                        $model->save(false);
			Yii::app()->user->setFlash('success',Yii::t('common','message_modify_success'));
                        $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
                    }
		}
		$this->render('update',array('model'=>$model));
	}

    public function actionDelete()//($id)
            
	{  
            $aa = Yii::app()->request->getParam('id');
        //$aa = Yii::app()->controller->id;    
        //echo '<pre>';print_r($aa);exit;
            $arrayRowId = $_GET['ids']['deviceID'];
            $arrayRowId = array("$arrayRowId");
            //$arrayRowId=  is_array(Yii::app()->request->getParam('id'))?Yii::app()->request->getParam('id'):array(Yii::app()->request->getParam('id'));
            //echo '<pre>';print_r($arrayRowId);exit;
            if(sizeof($arrayRowId)>0)
            {
                $arrayRowId=$this->findRelated($arrayRowId);
                
                $criteria=new CDbCriteria;
                 $criteria->addInCondition('deviceID', $arrayRowId);
                 //echo '<pre>';print_r($arrayRowId);exit;               
                if(CActiveRecord::model('GpsDevice')->deleteAll($criteria))
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
	
        public function findRelated($input)
        {
            $criteria=new CDbCriteria;
            $criteria->condition='deviceID IN ( '.implode(",",$input).' )';
            
            return $input; 
        }
        
	public function actionIndex()
	{
               
                                
            $model=new GpsDevice('search');
            
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['GpsDevice']))
                    $model->attributes=$_GET['GpsDevice'];
            //echo '<pre>';print_r($model);exit;
            $this->render('index',array('model'=>$model,'dataSet'=>$model->search()));
	}


	public function loadModel($id)
	{
		$model=Gpsdevice::model()->findByPk($id);
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
        
        
        protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'vehicleType':
                $return = $this->vehicleType[$data->vehicleType];
                break;
            case 'creationTime':
                $timestamp = $data->creationTime;
                $timestamp = gmdate("Y-m-d H:i:s", $timestamp);
                $return = $timestamp;
                break;
        }
        return $return;
    }
}

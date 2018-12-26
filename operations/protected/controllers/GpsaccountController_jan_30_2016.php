<?php

class GpsaccountController extends Controller
{
    
    public function actionCreate()
	{
		$model['ga']=new GpsAccount;
		//echo '<pre>';print_r($model['ga']);exit;
		if(Yii::app()->request->isPostRequest)
		{
			$model['ga']->attributes=$_POST['GpsAccount'];
                        $model['ga']->speedUnits = 1;
                        $model['ga']->displayName = $_POST['contactName'];
                        $model['ga']->distanceUnits = 1;
                        $model['ga']->temperatureUnits = 1;
                        $model['ga']->currencyUnits = 'INR';
                        $model['ga']->allowNotify=1;
                        $model['ga']->timeZone = 'IST';
                        $model['ga']->geocoderMode = 3;
                        $model['ga']->privateLabelName = '*';
                        $model['ga']->description = $_POST['GpsAccount']['contactName'];
                        $model['ga']->creationTime = time();
                        $model['ga']->accountID = strtolower($model['ga']->accountID);
                        //echo '<pre>';print_r($model['ga']->attributes);exit;
			if($model['ga']->validate()){
                            $model['ga']->save(false);
				Yii::app()->user->setFlash('success',Yii::t('common','message_create_success'));
				$this->redirect('index');
                        }
		}
                //echo '<pre>';print_r($model['ga']);exit;
		$this->render('create',array('model'=>$model['ga']));
	}

	public function actionUpdate()
	{           
                $model['gd']=new GpsDevice;
                //$model['ga']=$this->loadModel($id);
                $pk = $_GET['ids'];
                $model['ga'] = GpsAccount::model()->find("accountID='$pk'");
                if(isset($_POST['GpsAccount']))
		{
			$model['ga']->attributes=$_POST['GpsAccount'];
                        /*$model['gd']->attributes=$_POST['GpsDevice']['0'];
                        $model['gd']->accountID=$_POST['GpsAccount']['accountID'];
                        $model['gd']->vehicleID =$_POST['GpsDevice']['0']['deviceID'];
                        $model['gd']->uniqueID =$_POST['GpsDevice']['0']['deviceID'];
                        $model['gd']->statusCodeState = 61717;
                        $model['gd']->supportedEncodings  =7;
                        $model['gd']->isActive  =1;
                        $model['gd']->creationTime =time();*/
                        //echo '<pre>';print_r($aa);exit;
                        if($model['gd']->isActive == 0 ){
                        Gpsdevice::model()->updateAll(array('isActive'=>0),"accountID='$pk'");
                        }
                        //echo '<pre>';print_r($nalist);exit;
			if($model['ga']->validate())
			{
                            $model['ga']->save(false);
                            Yii::app()->user->setFlash('success',Yii::t('common','message_modify_success'));
                            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
                        }
		}
                //$model['gd']=GpsDevice::model()->findAll("accountID='$aa'");
                //echo '<pre>';print_r($model['gd']);exit;
		$this->render('update',array('model'=>$model['ga'],'model1'=>$model['gd']));
                
	}

    public function actionDelete()//($id)
	{   $arrayRowId=  is_array(Yii::app()->request->getParam('id'))?Yii::app()->request->getParam('id'):array(Yii::app()->request->getParam('id'));
            //echo '<pre>';print_r($arrayRowId['0']);exit;
            if($arrayRowId['0'] ==""){
                $aa = $_GET['ids'];
        if(sizeof($aa)>0)
            {   //$arrayRowId = $_GET['ids'];
            
            $aaa = array("$aa");
                $aaa=$this->findRelated($aaa);
                //echo '<pre>';print_r($aaa);exit;
                $criteria=new CDbCriteria;
                 $criteria->addInCondition('accountID', $aaa);
                 //echo $criteria;exit;               
                if(CActiveRecord::model('GpsAccount')->deleteAll($criteria))
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
            }else{
        
        $arrayRowId=  is_array(Yii::app()->request->getParam('id'))?Yii::app()->request->getParam('id'):array(Yii::app()->request->getParam('id'));
        if(sizeof($arrayRowId)>0)
            {   //$arrayRowId = $_GET['ids'];
                $arrayRowId=$this->findRelated($arrayRowId);
                echo '<pre>';print_r($arrayRowId);exit;
                $criteria=new CDbCriteria;
                 $criteria->addInCondition('accountID', $arrayRowId);
                 //echo $criteria;exit;               
                if(CActiveRecord::model('GpsAccount')->deleteAll($criteria))
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
            }
        
            if(!isset($_GET['ajax']))
                $this->redirect(base64_decode (Yii::app()->request->getParam('backurl')));
	}
	public function findRelated($input)
        {
            $criteria=new CDbCriteria;
            $criteria->condition='accountID IN ( '.implode(",",$input).' )';
            
            return $input; 
        }
        
	public function actionIndex()
	{
		
            $model['ga']=new GpsAccount('search');
        //exit("here");    
            $model['ga']->unsetAttributes();
            if(isset($_GET['GpsAccount']))
                    $model['ga']->attributes=$_GET['GpsAccount'];
            //echo '<pre>';print_r($model['ga']);exit;
            $this->render('index',array('model'=>$model['ga'],'dataSet'=>$model['ga']->search()));
			
	}
        
        protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'creationTime':
                $timestamp = $data->creationTime;
                $timestamp = gmdate("Y-m-d H:i:s", $timestamp);
                $return = $timestamp;
                break;
        }
        return $return;
    }


	public function loadModel($id)
	{
		$model['ga']=Trucktype::model()->findByPk($id);
                //echo '<pre>';print_r($model['ga']);exit;
		if($model['ga']===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model['ga'];
	}


}

<?php

class InsuranceController extends Controller
{
    public function actionCreate()
	{
            $model=new Customerinsurance;
            //if(isset($_POST['Customerinsurance']))
            if(Yii::app()->request->isPostRequest)
            {
                $model->attributes=$_POST['Customerinsurance'];
                if($model->save()){
                    Yii::app()->user->setFlash('success',Yii::t('common','message_create_success'));
                    $this->redirect('index');
                }
            }
            $this->render('create',array('model'=>$model));
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
                //echo Yii::app()->user->id.'<pre>';print_r($_SESSION);EXIT;
		if(isset($_POST['Customerinsurance']))
		{
                    //echo '<pre>';print_r($_POST);exit;
                    $model->attributes=$_POST['Customerinsurance'];
                    $model->id_admin=(int)Yii::app()->user->id;
                    if($model->save()){
                        //echo "value of ".$model->id_customer;exit;
                    if($_POST['Customerinsurance']['status']=='Quote'){    
                            $sql='select d.device_id from {{gps_login_device}} d,{{customer}} c where c.id_customer='.$model->id_customer.' and (c.mobile=d.username or c.gps_account_id=d.username)';
                            $rows=Yii::app()->db->createCommand($sql)->queryAll();
                            $devices=array();
                            foreach($rows as $k=>$row){
                                $devices[]=$row['device_id'];
                            }
                            //echo '<pre>';print_r($devices);exit;
                            Library::sendPushNotification(array('devices'=>$devices,'message'=>array('message'=>'Insurance Quote ready for '.$model->vehicle_number,'type'=>'insurance')));
                        }
                        Yii::app()->user->setFlash('success',Yii::t('common','message_modify_success'));
                        $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
                    }
		}
		$this->render('update',array('model'=>$model));
	}

        public function actionDelete()//($id)
	{
            $arrayRowId=  is_array(Yii::app()->request->getParam('id'))?Yii::app()->request->getParam('id'):array(Yii::app()->request->getParam('id'));
            if(sizeof($arrayRowId)>0)
            {
                //$arrayRowId=$this->findRelated($arrayRowId);
                $criteria=new CDbCriteria;
                $criteria->addInCondition('id_customer_insurance', $arrayRowId);
                //echo '<pre>';print_r($arrayRowId);exit;
                $custObjs = Customerinsurance::model()->findAll($criteria);
				foreach ($custObjs as $custObj) {
					//echo Library::getTruckUploadLink() . $custObj->file;
                    @unlink(Library::getTruckUploadPath() . $custObj->file);
                }
                //exit('here');
                if(CActiveRecord::model('Customerinsurance')->deleteAll($criteria))
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
            $model=new Customerinsurance('search');
            
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Customerinsurance']))
                $model->attributes=$_GET['Customerinsurance'];
            //echo '<pre>';print_r($model);exit;
            $this->render('index',array('model'=>$model,'dataSet'=>$model->search()));
	}


	public function loadModel($id)
	{
		$model=Customerinsurance::model()->findByPk($id);
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
            
            case 'type':
                //echo '<pre>';print_r($this->customerType);exit;
                $return = $this->customerType[$data->type];
                break;
            case 'approved':
                $return =$this->approved[$data->approved];
                break;
            case 'file':
                $return = $data->file!=""?'<a href="'.Library::getTruckUploadLink().$data->file.'" target="_blank">View</a>':'';
                break;
        }
        return $return;
    }
}

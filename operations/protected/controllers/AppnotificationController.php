<?php

class AppnotificationController extends Controller
{
        public function actionCreate()
	{
            $model=new Notificationbackend;
            if(Yii::app()->request->isPostRequest)
            {
                $post=$_POST['Notificationbackend'];
                //echo '<pre>';print_r($post);//exit;
                $model->attributes=$post;
                if($model->validate()){
                    $model->id_admin=Yii::app()->user->id;
                    $model->sent_to=implode(',',$post['sent_to']);
                    $model->save();
                    
                    if(in_array('All',$post['sent_to'])){
                        $srch="ld.id_gps_login_device!=0";
                        $noteModel=new Notification;
                        $noteModel->info=$post['info'];
                        $noteModel->visibility='All';
                        $noteModel->save(false);
                    }else if(in_array('All Gps Users',$post['sent_to'])){
                        $srch='lower(ld.username) in (select distinct lower(gps_account_id) from {{customer}} where gps_account_id!="")';
                        $noteModel=new Notification;
                        $noteModel->accountID=$v;
                        $noteModel->info=$post['info'];
                        $noteModel->visibility='Gps';
                        $noteModel->save(false);
                    }else{
                        $srch='ld.username in ("'.implode('","',$post['sent_to']).'")';
                        foreach($post['sent_to'] as $k=>$v){
                            $noteModel=new Notification;
                            $noteModel->accountID=$v;
                            $noteModel->info=$post['info'];
                            $noteModel->visibility='Single';
                            $noteModel->save(false);
                        }
                    }
                    //echo $srch;
                    //exit("<br/>hell");
                    
                    //$pushSql = "select distinct ld.device_id from {{gps_login_device}} ld  where  ".$srch;
                    $pushSql = "select ld.device_id,ld.username from {{gps_login_device}} ld  where  ".$srch." group by ld.device_id";
					//echo $pushSql;exit;
                    $pushRows = Yii::app()->db->createCommand($pushSql)->queryAll();
                    $devices = array();
                    foreach ($pushRows as $k => $row) {
                        //$devices[] = $row['device_id'];
						if(!in_array(strtolower($row['username']),$_POST['Notificationbackend']['ignore']) ){
							$devices[] = $row['device_id'];
						}
                    }
					//echo '<pre>';print_r($devices);exit;
					//exit();
                    $pushArray=array('devices' => $devices, 'message' => array('message' =>$post['info'], 'type' => 'general'));
                    $result = Library::sendPushNotification($pushArray);
                    Gpslogindevice::updateDuplicateDevices($devices,$result);
                    //echo '<pre>';print_r($pushArray);
					//echo $result;
					//exit("last");
                    Yii::app()->user->setFlash('success',Yii::t('common','message_create_success'));
                    $this->redirect('index');
                }
            }
            //exit("here is");
            $this->render('create',array('model'=>$model));
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['Notificationbackend']))
		{
			$model->attributes=$_POST['Notificationbackend'];
			if($model->save())
				 Yii::app()->user->setFlash('success',Yii::t('common','message_modify_success'));
                    $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
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
                $criteria->addInCondition('id_franchise', $arrayRowId);
                                
                if(CActiveRecord::model('Notificationbackend')->deleteAll($criteria))
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
            $model=new Notificationbackend('search');
            
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Notificationbackend']))
                $model->attributes=$_GET['Notificationbackend'];
            //echo '<pre>';print_r($model);exit;
            $this->render('index',array('model'=>$model,'dataSet'=>$model->search()));
	}


	public function loadModel($id)
	{
		$model=Notificationbackend::model()->findByPk($id);
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
}

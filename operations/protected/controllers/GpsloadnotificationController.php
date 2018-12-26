<?php

class GpsloadnotificationController extends Controller
{
    public function accessRules() {
        return $this->addActions(array('addUser','deleteuser','sendnotification'));
    }
        public function actionCreate()
	{       $model['c'] = new Customer;
                $model['ga']=new Gpsalerts;
                $model['gau']=new Gpsalertsusers;
		if(Yii::app()->request->isPostRequest)
		{
			$model['ga']->attributes=$_POST['Gpsalerts'];
                        $tt = $_POST['Gpsalerts'][id_truck_type];
                        $truck=Trucktype::model()->findAll(array('select'=>'id_truck_type, title','condition'=>'id_truck_type='.$tt));     
                        $model['ga']->id_truck_type_title = $truck[0]['title'];
						
                        $gt = $_POST['Gpsalerts'][id_goods_type];
                        $goods=Goodstype::model()->findAll(array('select'=>'id_goods_type, title','condition'=>'id_goods_type='.$gt));     
                        
			$model['ga']->id_goods_type_title = $goods[0]['title'];
                        //echo '<pre>';print_r($model['ga']->attributes);exit;
			if($model['ga']->validate()){
				$model['ga']->save();
				Yii::app()->user->setFlash('success',Yii::t('common','message_create_success'));
				$this->redirect('index');
			}
		}
                $records['customer'] = Customer::model()->findAll('islead=0 and status=1 and trash=0 and type!="G"');
                //echo '<pre>';print_r($records['customer']);exit;
		$this->render('create',array('records' => $records,'model'=>$model));
	}
        
        public function actionsendnotification(){
            $arrayRowId=  is_array(Yii::app()->request->getParam('id'))?Yii::app()->request->getParam('id'):array(Yii::app()->request->getParam('id'));
            if(sizeof($arrayRowId)>0)
            {
               foreach($arrayRowId as $k=>$v){
                   $model=Gpsalerts::model()->find('id_gps_alerts="'.$v.'"');
                   //$message="Hurry Up!!Load available from ".$model->source." to ".$model->destination;
				   $price=$model->price!="0.00"?", Rs.".$model->price:"";
                   $message=$model->source." To ".$model->destination.", ".$model->id_truck_type_title.$price;
				   //exit("message ".$message);
				   $devices=array();
                   if($model->sendtoall){
                       /*$Custrows=Yii::app()->db->createCommand("select distinct gld.device_id from eg_gps_login_device gld,eg_customer c where c.mobile not in ('9963470257') and (gld.username=c.mobile) or (gld.username=c.gps_account_id) and c.type in ('C','T')")->queryAll();
					   
					   //$Custrows=Yii::app()->db->createCommand("select distinct gld.device_id from eg_gps_login_device gld,eg_customer c where (gld.username=c.mobile) or (gld.username=c.gps_account_id) and c.type in ('C','T')")->queryAll();
                       foreach($Custrows as $row){
                           $devices[]=$row[device_id];
                       }
						echo "<br/>in all message : ".$message."<pre>";print_r($devices); echo "</pre>";//exit;
						echo '<pre>';print_r(array('devices'=>$devices,'message'=>array('message'=>$message,'type'=>'load')));//exit;
						*/
						$devices=Customer::model()->getMatchingDeviceList(array('source'=>$model->source,'destination'=>$model->destination));
                       $result=Library::sendPushNotification(array('devices'=>$devices,'message'=>array('message'=>$message,'type'=>'load')));
                       echo $result;//exit;
					   Gpsalerts::model()->updateAll(array('sent_count'=>count($devices),'notified'=>'1'),'id_gps_alerts="'.$v.'"');
                   }else{
					   //echo "select distinct gld.device_id from eg_gps_alerts_users gau,eg_gps_login_device gld where (gld.username=gau.id_customer_mobile or gld.username=gau.gps_account_id) and gau.id_gps_alerts='".$model->id_gps_alerts."'";
                       $rows=Yii::app()->db->createCommand("select distinct gld.device_id from eg_gps_alerts_users gau,eg_gps_login_device gld where (gld.username=gau.id_customer_mobile or gld.username=gau.gps_account_id) and gau.id_gps_alerts='".$model->id_gps_alerts."'")->queryAll();
                       foreach($rows as $k1=>$v1){
                           $devices[]=$v1['device_id'];
                       }
                       $result=Library::sendPushNotification(array('devices'=>$devices,'message'=>array('message'=>$message,'type'=>'load')));
					   echo $result;
                       Gpsalerts::model()->updateAll(array('notified'=>'1'),'id_gps_alerts="'.$v.'"');
					   echo "<br/>in selected message : ".$message."<pre>";print_r($devices); echo "</pre>";
                   }
				   //echo "value of ".$v;
               }
            }else
            {
                Yii::app()->user->setFlash('alert',Yii::t('common','message_checkboxValidation_alert'));
                Yii::app()->user->setFlash('error','Failed to send notification');
            }
			//exit;
            if(!isset($_GET['ajax']))
                $this->redirect(base64_decode (Yii::app()->request->getParam('backurl')));
            
        }
        
        public function actionAddUser($id) {
        //echo '<pre>';print_r($_POST['Gpsalertsusers']);exit;
        if (Yii::app()->request->getIsAjaxRequest() && isset($_POST)) {
            $model['gau']->attributes = $_POST['Gpsalertsusers'];
            $gau = new Gpsalertsusers();
            //echo $aa;exit;
            $gau->id_gps_alerts = $id;
                   // echo '<pre>';print_r($gau);exit;
            
            $model['c'] = new Customer;
            $xCust = explode(",", $_POST['Gpsalertsusers']['id_customer_mobile']);
            $custObj = Customer::model()->find('idprefix="' . $xCust[0] . '" or mobile="' . $xCust[3] . '"');
            //echo '<pre>';print_r($custObj);exit;
            $id_customer = $custObj->id_customer;
                if ($custObj->id_customer) {
                    $id_customer = $custObj->id_customer;
                }else{
                    Yii::app()->user->setFlash('error', 'Invalid user!!');
                    $this->redirect('create');
                }
            $gau->id_customer_mobile = $xCust['3'];
            $gau->gps_account_id = $xCust['5'];
            
            
            $gau->save(false);
            $content = "<tbody ><tr>
                            <td>" . $gau->id_customer_mobile . "</td>
                            <td>" . $gau->gps_account_id . "</td>";
            echo $content;
        }
    }
    public function actiondeleteuser() {
        if (Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest) {
            Gpsalertsusers::model()->deleteAll('id_gps_alerts_users="'.$_POST['id'].'"');
        }
	Yii::app()->end();
    }

	public function actionUpdate($id)
	{
           $model['c'] = new Customer;
           $model['gai'] = new Gpsalertsinterested();
            //echo '<pre>';print_r($model['gai']->attributes);exit;
		$model=$this->loadModel($id);
		if(isset($_POST['Gpsalerts']))
		{
			$model['ga']->attributes=$_POST['Gpsalerts'];
                        $tt = $model['ga']->id_truck_type;
                        $truck=Trucktype::model()->findAll(array('select'=>'id_truck_type, title','condition'=>'id_truck_type='.$tt));     
                        $model['ga']->id_truck_type_title = $truck[0]['title'];
                        $gt = $model['ga']->id_goods_type;
                        $goods=Goodstype::model()->findAll(array('select'=>'id_goods_type, title','condition'=>'id_goods_type='.$gt));     
                        $model['ga']->id_goods_type_title = $goods[0]['title'];
                        $aa = $model['ga']->sendtoall;
                        //Gpsalertsusers::model()->updateAll(array('id_gps_alerts'=>5),'id_gps_alerts_users='.(int)$_GET['id']);
                        //echo $aa;exit;
                        //echo '<pre>';print_r($model);exit;
                        if($model['ga']->save())
				 Yii::app()->user->setFlash('success',Yii::t('common','message_modify_success'));
                    $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
		}
                $records['customer'] = Customer::model()->getApprovedActiveCustomers();
                //echo '<pre>';print_r($rows);exit;
		$this->render('update',array('records' => $records,'model'=>$model));
	}
        
       

    public function actionDelete()//($id)
	{
            $arrayRowId=  is_array(Yii::app()->request->getParam('id'))?Yii::app()->request->getParam('id'):array(Yii::app()->request->getParam('id'));
            if(sizeof($arrayRowId)>0)
            {
                $arrayRowId=$this->findRelated($arrayRowId);
                
                $criteria=new CDbCriteria;
                 $criteria->addInCondition('id_gps_alerts', $arrayRowId);
                                
                if(CActiveRecord::model('Gpsalerts')->deleteAll($criteria))
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
            $criteria->condition='id_gps_alerts IN ( '.implode(",",$input).' )';
            //$states=State::model()->findAll($criteria); 
            if(sizeof($states)>0)
            {
                $items="";
                $input=array_flip($input);
                foreach($states as $state):
                    $items.=$prefix.strip_tags($state->name);
                    $prefix=",";
                    unset($input[$state->accountID]);
                endforeach;
                $input=array_flip($input);

                Yii::app()->user->setFlash('alert',Yii::t('countries','warning_country', array('{details}'=>$items)));
            }
            return $input; 
        }
        
	public function actionIndex()
	{
               //$rows=Gpslogindevice::model()->findAll(array('select'=>'distinct device_id'));
               //echo '<pre>';print_r($rows);exit;                 
            $model=new Gpsalerts('search');
            
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Gpsalerts']))
                    $model->attributes=$_GET['Gpsalerts'];
            //echo '<pre>';print_r($model);exit;
            $this->render('index',array('model'=>$model,'dataSet'=>$model->search()));
	}


	public function loadModel($id)
	{
		$model['ga']=Gpsalerts::model()->findByPk($id);
                $model['gau'] = new Gpsalertsusers();
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'manufacturer-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


	
}

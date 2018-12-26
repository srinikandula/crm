<?php

class GpstrucknotificationController extends Controller
{
    public function accessRules() {
        return $this->addActions(array('addUser','deleteuser','sendnotification'));
    }
        public function actionCreate()
	{       $model['c'] = new Customer;
                $model['ga']=new Notifytransporteravailabletrucks;
                $model['gau']=new Notifytransporteravailabletrucksusers;
		if(Yii::app()->request->isPostRequest)
		{
			$model['ga']->attributes=$_POST['Notifytransporteravailabletrucks'];
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
                   $model=Gpsalerts::model()->find('id_notify_transporter_available_trucks="'.$v.'"');
                   $message="Hurry Up!!Trucks available from ".$model->source_city." to ".$model->destination_city;
                   $devices=array();
                   if($model->sendtoall){
                       $Custrows=Yii::app()->db->createCommand("select distinct gld.device_id from eg_gps_login_device gld,eg_customer c where (gld.username=c.mobile) or (gld.username=c.gps_account_id) and c.type='TR'")->queryAll();
                       foreach($Custrows as $row){
                           $devices[]=$row[device_id];
                       }
						echo "<br/>in all message : ".$message."<pre>";print_r($devices); echo "</pre>";
						
                       $result=Library::sendPushNotification(array('devices'=>$devices,'message'=>array('message'=>$message,'type'=>'truckavailable')));
                       echo $result;
			//Gpsalerts::model()->updateAll(array('notified'=>'1'),'id_gps_alerts="'.$v.'"');
                        Notifytransporteravailabletrucks::model()->updateAll(array('notified'=>'1'),'id_notify_transporter_available_trucks="'.$v.'"');
                   }else{
                        //echo "select distinct gld.device_id from eg_gps_alerts_users gau,eg_gps_login_device gld where (gld.username=gau.id_customer_mobile or gld.username=gau.gps_account_id) and gau.id_gps_alerts='".$model->id_gps_alerts."'";
                       /*$ums=Yii::app()->db->createCommand("select mobile,gps_account_id from eg_customer where id_customer in (select id_customer from eg_notify_transporter_available_trucks_users where id_notify_transporter_available_trucks='".$v."')")->queryAll();
                       
                       $results=array();
                       foreach($ums as $um){
                            $results[]=$um['mobile'];
                            if($um['gps_account_id']!=""){$results[]=$um['gps_account_id'];}
                       }*/
                       
                       $rows=Yii::app()->db->createCommand("select gld.username,gld.device_id from eg_notify_transporter_available_trucks_users ntatu,eg_customer c,eg_gps_login_device gld where (ntatu.id_notify_transporter_available_trucks='".$v."' and ntatu.id_customer=c.id_customer) and (c.mobile=gld.username or c.gps_account_id=gld.username)")->queryAll();
                       foreach($rows as $k1=>$v1){
                           $devices[]=$v1['device_id'];
                       }
                       $result=Library::sendPushNotification(array('devices'=>$devices,'message'=>array('message'=>$message,'type'=>'truckavailable')));
					   echo $result;
                       Notifytransporteravailabletrucks::model()->updateAll(array('notified'=>'1'),'id_notify_transporter_available_trucks="'.$v.'"');
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
        //if (Yii::app()->request->getIsAjaxRequest() && isset($_POST)) {
            if (1) {
            $xCust = explode(",", $_POST['Gpsalertsusers']['id_customer_mobile']);
            $custObj = Customer::model()->find('idprefix="' . $xCust[0] . '" or mobile="' . $xCust[3] . '"');
            if ($custObj->id_customer) {
                $gau = new Notifytransporteravailabletrucksusers();
                $gau->id_notify_transporter_available_trucks = $id;
            
                $gau->id_customer = $custObj->id_customer;
                $gau->save(false);
            
            $content = "<tbody ><tr>
                            <td>" . $custObj->fullname." ".$custObj->mobile . "</td>
                            <td>" . $custObj->gps_account_id . "</td>";
            echo $content;
            }
        }
    }
    public function actiondeleteuser() {
        if (Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest) {
            Notifytransporteravailabletrucksusers::model()->deleteAll('id_notify_transporter_available_trucks_users="'.$_POST['id'].'"');
        }
	Yii::app()->end();
    }

	public function actionUpdate($id)
	{
           $model['c'] = new Customer;
           $model['gai'] = new Notifytransporteravailabletruckscustomers();
            //echo '<pre>';print_r($model['gai']->attributes);exit;
		$model=$this->loadModel($id);
		if(isset($_POST['Notifytransporteravailabletrucks']))
		{
			$model['ga']->attributes=$_POST['Notifytransporteravailabletrucks'];
                        
                        if($model['ga']->validate()){
                            $model['ga']->save();
                            Yii::app()->user->setFlash('success',Yii::t('common','message_modify_success'));
                            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
                        }
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
                 $criteria->addInCondition('id_notify_transporter_available_trucks', $arrayRowId);
                                
                if(CActiveRecord::model('Notifytransporteravailabletruck')->deleteAll($criteria))
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
               
                                
            $model=new Notifytransporteravailabletrucks('search');
            
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Notifytransporteravailabletrucks']))
                    $model->attributes=$_GET['Notifytransporteravailabletrucks'];
            //echo '<pre>';print_r($model);exit;
            $this->render('index',array('model'=>$model,'dataSet'=>$model->search()));
	}


	public function loadModel($id)
	{
		$model['ga']=Notifytransporteravailabletrucks::model()->findByPk($id);
                $model['gau'] = new Notifytransporteravailabletrucksusers();
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

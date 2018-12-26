<?php

class GpsdevicemgmtController extends Controller
{
    public $customerTypeLinks;
    
    public function accessRules() {
        return $this->addActions(array('getDeviceDetails'));
    }
    
    public function trimToLower($field){
        $return=trim($field); //trim
        $return=str_replace(" ","",$return);         //replace empty space 
        $return=  strtolower($return); //change to lower case
        return $return;
    }
    
    public function actiongetDeviceDetails(){
        //Yii::app()->db->enableProfiling=0;
        $accountid=$_GET['accountid'];
        $flag=0;
        if ($accountid!="") {
            $device=GpsDevice::model()->findAll(array("select"=>"vehicleType,deviceID,installedBy,vehicleModel,truckTypeId,lastValidLatitude,lastValidLongitude,lastValidSpeedKPH,lastGPSTimestamp,imeiNumber,simPhoneNumber,creationTime","condition"=>"accountID='".$accountid."'"));
            $account=  GpsAccount::model()->find(array("select"=>"creationTime,vehicleType,smsEnabled,contactName,contactPhone,contactEmail,contactAddress,isActive,stopDurationLimit,overSpeedLimit","condition"=>"accountID='".$accountid."'"));
            $flag=1;
        }
        $this->renderPartial('_getDeviceDetails',array('account'=>$account,'model'=>$device,'flag'=>$flag));
    }
    
    public function actionCreate()
	{
		$model['ga']=new GpsAccount;
		//echo '<pre>';print_r($model['ga']);exit;
		if(Yii::app()->request->isPostRequest)
		{
                        /*if($model['ga']->accountID!=$_POST['GpsAccount']['accountID']){
                            $obj=GpsAccount::model()->find(array('select'=>'count(*)','condition'=>'accountID="'.$_POST['GpsAccount']['accountID'].'"'));
                            if($obj->count)
                            exit("in if");
                        }else{
                            exit("in else");
                        }*/
			$model['ga']->attributes=$_POST['GpsAccount'];
                        $model['ga']->speedUnits = 1;
                        $model['ga']->displayName = $_POST['GpsAccount']['contactName'];
                        $model['ga']->distanceUnits = 1;
                        $model['ga']->temperatureUnits = 1;
                        $model['ga']->currencyUnits = 'INR';
                        $model['ga']->allowNotify=1;
                        $model['ga']->timeZone = 'IST';
                        $model['ga']->geocoderMode = 3;
                        $model['ga']->privateLabelName = '*';
                        $model['ga']->description = $_POST['GpsAccount']['contactName'];
                        $model['ga']->creationTime = time();
                        //$model['ga']->accountID = strtolower($model['ga']->accountID);
                        $model['ga']->accountID =$this->trimToLower($_POST['GpsAccount']['accountID']);
                        //echo '<pre>';print_r($model['ga']->attributes);exit;
			if($model['ga']->validate()){
                            $model['ga']->save(false);
                            if($_POST['GpsAccount']['customer_type']!='GPS'){
                                $this->createCustomer($_POST['GpsAccount']);
                            }
                            $message="Hello ".$_POST['GpsAccount']['contactName'].",You can login now with Username:".$_POST['GpsAccount']['accountID'].",Password:".$_POST['GpsAccount']['password'].".Your devices will be activated in few mins.Thank you.";
                            Library::sendSingleSms(array('to'=>$_POST['GpsAccount']['contactPhone'],'message'=>$message));
                            Yii::app()->user->setFlash('success',Yii::t('common','message_create_success'));
                            $this->redirect('index');
                        }
		}
                //echo '<pre>';print_r($model['ga']);exit;
		$this->render('create',array('model'=>$model['ga']));
	}
        
        public function createCustomer($data){
            	
            if($data['accountID']!="" && $data['password']!="" && $data['contactPhone']!="" && $data['contactName']!=""){
                        $findCObj=Customer::model()->find('mobile="'.$data[contactPhone].'" and type!="G"');
                        $encyPwd=CPasswordHelper::hashPassword($data[password]);
                        if(!is_object($findCObj)){
                        $custObj=new Customer;
                        $custObj->gps_account_id=$data[accountID];
                        $custObj->islead=0;
                        $custObj->type=$data['customer_type'];
                        $custObj->fullname=$data[contactName];
                        $custObj->date_created=new CDbExpression('NOW()');
                        $custObj->mobile=$data[contactPhone];
                        $custObj->password=$encyPwd;
                        $custObj->status=$data['isActive'];
                        $custObj->approved=$data['isActive'];
                        $custObj->save(false);

                        $idprefix=Library::getIdPrefix(array('id'=>$custObj->id_customer,'type'=>$data['customer_type']));
                        Customer::model()->updateAll(array('idprefix'=>$idprefix),'id_customer="'.$custObj->id_customer.'"');        
                        $custLeadObj = new Customerlead;
                        $custLeadObj->id_customer = $custObj->id_customer;
                        $custLeadObj->lead_source = 'Truck App';
                        $custLeadObj->lead_status = 'Initiated';
                        $custLeadObj->save(false);
                    }else{
                        Customer::model()->updateAll(array('password'=>$encyPwd,'fullname'=>$data['contactName'],'islead'=>0,'type'=>$data['customer_type'],'gps_account_id'=>$data['accountID'],'status'=>$data['isActive'],'approved'=>$data['isActive']),'id_customer="'.$findCObj->id_customer.'"');
                    }
                }
        }

	public function actionUpdate()
	{       
                $this->customerTypeLinks=array('T'=>"truckowner","TR"=>"transporters","C"=>"cagent");
                $model['gd']=new GpsDevice;
                //$model['ga']=$this->loadModel($id);
                $pk = $_GET['ids'];
                $model['ga'] = GpsAccount::model()->find("accountID='$pk'");
                if(isset($_POST['GpsAccount']))
		{
			$accModifyFlag=0;
                        $accountIDPrev="";
                        if($model['ga']->accountID!=$_POST['GpsAccount']['accountID']){
                            $accModifyFlag=1;
                            $accountIDPrev=$model['ga']->accountID;
                        }
                        $model['ga']->attributes=$_POST['GpsAccount'];
                        $model['ga']->accountID =$this->trimToLower($_POST['GpsAccount']['accountID']);
                        /*$model['gd']->attributes=$_POST['GpsDevice']['0'];
                        $model['gd']->accountID=$_POST['GpsAccount']['accountID'];
                        $model['gd']->vehicleID =$_POST['GpsDevice']['0']['deviceID'];
                        $model['gd']->uniqueID =$_POST['GpsDevice']['0']['deviceID'];
                        $model['gd']->statusCodeState = 61717;
                        $model['gd']->supportedEncodings  =7;
                        $model['gd']->isActive  =1;
                        $model['gd']->creationTime =time();*/
                        //echo '<pre>';print_r($aa);exit;
                        /*if($model['gd']->isActive == 0 ){
                        Gpsdevice::model()->updateAll(array('isActive'=>0),"accountID='$pk'");
                        }*/
                        //echo '<pre>';print_r($nalist);exit;
			if($model['ga']->validate())
			{
                            $model['ga']->save(false);
                            if($_POST['GpsAccount']['customer_type']!='GPS'){
                                $this->createCustomer($_POST['GpsAccount']);
                            }
                            
                            if($accModifyFlag && $_POST['GpsAccount']['accountID']!="" && $accountIDPrev!=""){
                                GpsDevice::model()->updateAll(array('accountID'=>$_POST['GpsAccount']['accountID']),'accountID="'.$accountIDPrev.'"');
                                GpsEventData::model()->updateAll(array('accountID'=>$_POST['GpsAccount']['accountID']),'accountID="'.$accountIDPrev.'"');
                                
                            }
                            //exit("at last");
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
            $info['damaged']=Yii::app()->db_gts->createCommand("select count(*) from Device where isDamaged=1")->queryScalar();
            $dt=time()-41400;
	    $info['notworking']=Yii::app()->db_gts->createCommand("select count(*) from Device where imeiNumber!='' and (accountID!='santosh' or accountID!='accounts') and lastGPSTimestamp <".$dt)->queryScalar();
            
            $rows=Yii::app()->db_gts->createCommand('SELECT installedById, COUNT( * ) AS devices, IF( devicePaymentStatus =  "",  "Open", devicePaymentStatus ) AS payment FROM Device WHERE imeiNumber!="" and devicePaymentStatus !=  "Confirmed" GROUP BY installedById, devicePaymentStatus')->queryAll();
            $adminObjs=Admin::model()->findAll();
            foreach($adminObjs as $adminObj){
                $admin[$adminObj->id_admin]=$adminObj->first_name." ".$adminObj->last_name;
                
            }
            
            foreach($rows as $row){
                $installs[$row[installedById]][$row[payment]]=$row[devices];
            }
            
            $paymentRows=Yii::app()->db_gts->createCommand('select d.installedById,d.devicePaymentStatus ,sum(adph.amount) as amount from Device d,Accountdeviceplanhistory adph where d.imeiNumber!="" and d.deviceID=adph.deviceID and d.devicePaymentStatus!="Confirmed" and adph.received=0 group by d.installedById,d.devicePaymentStatus')->queryAll();
            
            foreach($paymentRows as $paymentRow){
                $payments[$paymentRow[installedById]][$paymentRow[devicePaymentStatus]]=$paymentRow['amount'];
            }
            
            //echo '<pre>';print_r($paymentRows);print_r($payments);echo '</pre>';
            $this->render('index',array('installs'=>$installs,'info'=>$info,'admin'=>$admin,'payments'=>$payments));
	}
        
        protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'creationTime':
                $timestamp = $data->creationTime+19800;
                $timestamp = gmdate("Y-m-d H:i:s", $timestamp);
                $return = $timestamp;
                break;
            case 'customer_type':
                $custObj=Customer::model()->find('gps_account_id="'.$data->accountID.'"');
                if((int)$custObj->id_customer){
                $return="<a  href='".$this->createUrl($this->customerTypeLinks[$custObj->type].'/update',array('id'=>$custObj->id_customer))."' target='_blank'>".$custObj->idprefix."</a>";
                }
                
                break;
            case 'no_of_vehicles':
                $return='<a class="btn-link" href="#" onclick="fngetDeviceDetails(\''.$data->accountID.'\');">'.$data->no_of_vehicles.'</a>';
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

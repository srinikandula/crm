<?php

class GpsaccountController extends Controller
{
    public $customerTypeLinks;
    
	public function accessRules() {
        return $this->addActions(array('getDeviceDetails'));
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
	
	/*public function trimToLower($field){
        $return=trim($field); //trim
        $return=str_replace(" ","",$return);         //replace empty space 
        $return=  strtolower($return); //change to lower case
        return $return;
    }*/

	public function actionCreate()
	{
		$model['ga']=new GpsAccount;
		//echo '<pre>';print_r($model['ga']);exit;
		if(Yii::app()->request->isPostRequest)
		{
			//echo '<pre>';print_r($_POST);exit;

			$model['ga']->attributes=$_POST['GpsAccount'];
                        $model['ga']->speedUnits = 1;
                        $model['ga']->displayName = $_POST['GpsAccount']['contactName'];
                        $model['ga']->distanceUnits = 1;
                        $model['ga']->temperatureUnits = 1;
                        $model['ga']->currencyUnits = 'INR';
                        $model['ga']->allowNotify=1;
                        $model['ga']->timeZone = 'IST';
                        $model['ga']->geocoderMode = 3;
                        //$model['ga']->privateLabelName = '*';
                        $model['ga']->description = $_POST['GpsAccount']['contactName'];
                        $model['ga']->creationTime = time();
						$model['ga']->createdById=Yii::app()->user->id;
                        //$model['ga']->accountID = strtolower($model['ga']->accountID);
						$model['ga']->accountID =Library::trimToLower($_POST['GpsAccount']['accountID']);
                        //echo '<pre>';print_r($model['ga']->attributes);exit;
			if($model['ga']->validate()){
				//exit("inside");
                            $model['ga']->save(false);
                            if($_POST['GpsAccount']['customer_type']!='GPS'){
                                $this->createCustomer($_POST['GpsAccount']);
                            }
					$this->addCOptDest(Library::trimToLower($_POST['GpsAccount']['accountID']));
					$message="Hello ".$_POST['GpsAccount']['contactName'].",You can login now with  Username:".Library::trimToLower($_POST['GpsAccount']['accountID']).",Password:".$_POST['GpsAccount']['password']." .Please download app from https://goo.gl/1dCVYf .Thank you.";
					Library::sendSingleSms(array('to'=>$_POST['GpsAccount']['contactPhone'],'message'=>$message));
				
				Yii::app()->user->setFlash('success',Yii::t('common','message_create_success'));
				$this->redirect('index');
                        }/*else{
							$errors=$model['ga']->getErrors();
							
							foreach($errors as $k=>$v){
								$errMsgs[]=$v[0];	
							}
							echo '<pre>';print_r($model['ga']->getErrors());
							print_r($errMsgs);
							exit;
							exit("outside");
						}*/
		}
                //echo '<pre>';print_r($model['ga']);exit;
		$data=Yii::app()->db_gts->createCommand("select accountID from Account where accountID!='' order by accountID asc")->queryAll();
		$this->render('create',array('data'=>$data,'model'=>$model['ga']));
	}
        
        public function createCustomer($data){
            //echo '<pre>';print_r($data);exit;		
            if($data['accountID']!="" && $data['password']!="" && $data['contactPhone']!="" && $data['contactName']!=""){
                        $findCObj=Customer::model()->find('mobile="'.$data[contactPhone].'" and type!="G"');
                        $encyPwd=CPasswordHelper::hashPassword($data[password]);
                        if(!is_object($findCObj)){
							//exit("in if".'mobile="'.$data[contactPhone].'" and type!="G"');
                        $custObj=new Customer;
                        $custObj->gps_account_id=Library::trimToLower($data[accountID]);
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
						//exit(" in if");
                    }else{
						//exit("in else");
						$idprefix=Library::getIdPrefix(array('id'=>$findCObj->id_customer,'type'=>$findCObj->type));
                        Customer::model()->updateAll(array('idprefix'=>$idprefix,'password'=>$encyPwd,'fullname'=>$data['contactName'],'islead'=>0,'type'=>$data['customer_type'],'gps_account_id'=>Library::trimToLower($data['accountID']),'status'=>$data['isActive'],'approved'=>$data['isActive']),'id_customer="'.$findCObj->id_customer.'"');
						//exit("in else");
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
			$oldcontactPhone=$model['ga']->contactPhone;

			$model['ga']->attributes=$_POST['GpsAccount'];
			$model['ga']->accountID =Library::trimToLower($_POST['GpsAccount']['accountID']);
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
							$this->addCOptDest(Library::trimToLower($_POST['GpsAccount']['accountID']));
							//echo $_POST['GpsAccount']['customer_type'].'<pre>';print_r($_POST);exit;
							if(($_POST['GpsAccount']['customer_type']!='GPS') && ($oldcontactPhone==$_POST['GpsAccount']['contactPhone'])){
							//	if(($_POST['GpsAccount']['customer_type']!='GPS')){
                                $this->createCustomer($_POST['GpsAccount']);
                            }

							if($accModifyFlag && $_POST['GpsAccount']['accountID']!="" && $accountIDPrev!=""){
                                GpsDevice::model()->updateAll(array('accountID'=>Library::trimToLower($_POST['GpsAccount']['accountID'])),'accountID="'.$accountIDPrev.'"');
                                GpsEventData::model()->updateAll(array('accountID'=>Library::trimToLower($_POST['GpsAccount']['accountID'])),'accountID="'.$accountIDPrev.'"');
                                
                            }

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
            $this->customerTypeLinks=array('T'=>"truckowner","TR"=>"transporters","C"=>"cagent");	
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
                $timestamp = $data->creationTime+19800;
                $timestamp = gmdate("Y-m-d H:i:s", $timestamp);
                $return = $timestamp;
                break;
            case 'customer_type':
                $custObj=Customer::model()->find('gps_account_id="'.$data->accountID.'"');
                if((int)$custObj->id_customer){
                    $return="<a href='".$this->createUrl($this->customerTypeLinks[$custObj->type].'/update',array('id'=>$custObj->id_customer))."' target='_blank'>".$custObj->idprefix."</a>";
                }
                break;
			case 'no_of_vehicles':
                $return='<a href="#" class="btn-link" onclick="fngetDeviceDetails(\''.$data->accountID.'\');">'.$data->no_of_vehicles.'</a>';
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

	public function addCOptDest($id)
    {
        GpsAccountOperatingDestinations::model()->deleteAll('accountID="'.$id.'"');
        foreach ($_POST['Customer']['operating_city'] as $k => $v) {
            if ($v['source'] == '' || $v['destination'] == '' ) {
                    continue;
            }

            $gDetails1 = Library::getGPDetails($v['source']);
            $gDetails2 = Library::getGPDetails($v['destination']);

            $model['cod'] = new GpsAccountOperatingDestinations;
            $model['cod']->accountID = $id;

            $model['cod']->source_city = $gDetails1['city'] == '' ? $gDetails1['input'] : $gDetails1['city'];
            $model['cod']->source_address = $gDetails1['input'];
            $model['cod']->source_state = $gDetails1['state'];
            $model['cod']->source_lat = $gDetails1['lat'];
            $model['cod']->source_lng = $gDetails1['lng'];

            $model['cod']->destination_city = $gDetails2['city'] == '' ? $gDetails2['input'] : $gDetails2['city'];
            $model['cod']->destination_address = $gDetails2['input'];
            $model['cod']->destination_state = $gDetails2['state'];
            $model['cod']->destination_lat = $gDetails2['lat'];
            $model['cod']->destination_lng = $gDetails2['lng'];
            $model['cod']->save(false);
        }
    }
}

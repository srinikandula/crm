<?php

class GpsdevicesController extends Controller {

    public $vehicleType = array('TK' => 'Truck', 'NTK' => 'Non Truck', 'TR' => 'Transporter');

    public function accessRules() {
        return $this->addActions(array('getDeviceDetails','downloadDevice','transferDevices','confirmDevicePayment', 'assignDevices', 'renewalPaid', 'addGpsPlan', 'deletegpsplan'));
    }

	public function actiongetDeviceDetails(){
        $deviceID=$_POST['deviceID'];
        if(Yii::app()->request->isPostRequest && $deviceID!="") {
            //echo "hello".$_POST['deviceID'];
			$row=Yii::app()->db_gts->createCommand("select concat(ceil(lastValidSpeedKPH*1.852),' ','Km/Hr') as speed,FROM_UNIXTIME(ifnull(lastUpdateTime,0), '%d-%m-%y %H:%i') as date_time,lastValidLongitude,lastValidLatitude from Device where deviceID='".$deviceID."'")->queryRow();
			$loc=Library::getGPBYLATLNGDetailsGoogle($row["lastValidLatitude"].",".$row["lastValidLongitude"]);

			echo $deviceID." is at ".$loc['address']." with speed ".$row['speed']." : ".$row['date_time'];
        }
            exit;
    }

	public function actiondownloadDevice(){
        
        if(Yii::app()->request->isPostRequest) {
            //echo "here";
            $expObj=new Export();
            $return=$expObj->downloadDeviceReport();
            exit;
        }
        $this->renderPartial('_downloadDevice');
    }

	public function actiontransferDevices() {
        $assignTo = $_POST['assignTo'];
        if(Yii::app()->request->isPostRequest && $assignTo) {
            $expImeis=explode(PHP_EOL,$_POST['imei']);
            $imeiqry="";
            $pre="";
            foreach($expImeis as $imei){
                $trmimei=trim($imei);
                if($trmimei!="" && strlen($trmimei)==15){
                    $imeiqry.=$pre."'".$trmimei."'";
                    $pre=",";
                }
            }
            if($imeiqry!=""){
                $exp_installedBy = explode("@@", $assignTo);
                //echo "update Device set installedBy='".$exp_installedBy[1]."',installedById='".$exp_installedBy[0]."' where imeiNumber in (".$imeiqry.")";
                //exit;
                Yii::app()->db_gts->createCommand("update Device set installedBy='".$exp_installedBy[1]."',installedById='".$exp_installedBy[0]."' where (accountID='santosh' or accountID='accounts' ) and imeiNumber in (".$imeiqry.")")->query();
                Yii::app()->user->setFlash('success', $imeiqry." Devices has been transferred to " . $exp_installedBy[1]);
                //echo '<pre>';print_r($exp);print_r($_POST);EXIT;
            }

            $this->redirect('transferDevices');
        }
        $assignToRows = Admin::model()->findAll(array("select" => "id_admin,first_name,last_name", "condition" => "status=1 order by first_name desc"));
        $this->renderPartial('_transferDevices', array('assignToRows' => $assignToRows));
    }

    public function actionconfirmDevicePayment() {
        $ids = Yii::app()->request->getParam('id');
        /*echo '<pre>';
        print_r($ids);
        exit;*/
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $exp=explode("##",$id);
                if(($exp[0]!='santosh' || $exp[0]!='accounts')  && $exp[2]!='Confirmed'){
                    $dateCreated=date('Y-m-d',$exp[3]);
                    GpsDevice::model()->updateAll(array('devicePaymentStatus'=>'Confirmed'),'accountID="'.$exp[0].'" and deviceID="'.$exp[1].'"');
                    //Accountdeviceplanhistory::model()->updateAll(array('received'=>1),'startTime"'.$dateCreated.'" and accountID="'.$exp[0].'" and deviceID="'.$exp[1].'"');
                    //Accountdeviceplanhistory::model()->updateAll(array('received'=>1),'accountID="'.$exp[0].'" and deviceID="'.$exp[1].'"');
					Accountdeviceplanhistory::model()->updateAll(array('received'=>1),'lower(deviceID)="'.strtolower($exp[1]).'"');
                }
            }
            Yii::app()->user->setFlash('success', 'Selected Device payments confirmed!!');
        }
        //$this->redirect('index');
		$this->redirect(Yii::app()->request->urlReferrer);
    }

    public function actionassignDevices() {
        $assignTo = $_POST['assignTo'];
        $file = $_FILES['file'];
        if (Yii::app()->request->isPostRequest && $assignTo) {
            //echo '<pre>';print_r($_POST);EXIT;
            $i = 0;
            $exp_installedBy = explode("@@", $assignTo);
            unset($_SESSION['gps_errors']);
            
            foreach ($_POST['Assign'] as $row) {
                if ($row['imeino'] != "" && $row['simno'] != "" && $row['simid'] != "") {
                    $model = new GpsDevice;
                    $model->accountID = 'accounts'; //earlier it was "santosh"
                    $model->attributes = $_POST['GpsDevice'];
                    $model->statusCodeState = 61717;
                    $model->ignitionIndex = 61717;
                    $model->supportedEncodings = 7;
                    $model->isActive = 1;
                    $model->creationTime = time();
                    $model->lastOdometerKM = 0;
                    $model->lastDistanceKM = 0;
                    $model->lastValidSpeedKPH = 0;
                    $model->installedById = $exp_installedBy[0];
                    $model->installedBy = $exp_installedBy[1];
                    $model->imeiNumber = trim($row['imeino']);
                    $model->deviceID = trim($row['imeino']);
                    $model->simPhoneNumber = trim($row['simno']);
                    $model->simID = trim($row['simid']);
                    $model->truckTypeId=18;
                    $model->vehicleModel='12 Tyre-21 Ton';
                    $model->vehicleType='TK';
                    
                    //$model->save(false);
                    if ($model->validate()) {
                        $model->save(false);
                        $i++;
                    }else{
                        $_SESSION['gps_errors'][]=$model->getErrors();
                    }
                    
                }
            }
            if ($i) {
                //sizeof($_SESSION['gps_errors'])
                Yii::app()->user->setFlash('success', $i . " Devices has been assigned to " . $exp_installedBy[1]);
                GpsEventData::model()->updateAll(array("odometerKM" => 0), '(accountID="santosh" or accountID="accounts") and odometerKM is NULL');
                GpsEventData::model()->updateAll(array("distanceKM" => 0), '(accountID="santosh" or accountID="accounts") and distanceKM is NULL');
                
                GpsDevice::model()->updateAll(array("lastDistanceKM" => 0), '(accountID="santosh" or accountID="accounts") and lastDistanceKM is NULL');
                GpsDevice::model()->updateAll(array("lastOdometerKM" => 0), '(accountID="santosh" or accountID="accounts") and lastOdometerKM is NULL');
            }
            $this->redirect('assignDevices');
        }
        $assignToRows = Admin::model()->findAll(array("select" => "id_admin,first_name,last_name", "condition" => "status=1 order by first_name desc"));
        $this->renderPartial('_assignDevices', array('assignToRows' => $assignToRows));
    }

    public function actionrenewalPaid() {
        $id = (int) $_POST['id'];
        $json['status'] = 0;
        //echo '<pre>';print_r($_POST);exit;
        if ($id != "" && Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest) {
            Accountdeviceplanhistory::model()->updateAll(array('received' => 1), 'id="' . $id . '"');
            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actiondeletegpsplan() {
        if (Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest) {
            Accountdeviceplanhistory::model()->deleteAll('id="' . $_POST['id'] . '"');
        }
        Yii::app()->end();
    }

    public function actionAddGpsPlan($id) {
        //echo '<pre>';print_r($_POST);exit;
        if (Yii::app()->request->getIsAjaxRequest() && $_POST[GpsDevice][update_on] != "" && (int) $_POST[Accountdeviceplanhistory][amount] != 0 && $_POST[Accountdeviceplanhistory][planName] != "") {
            //echo '<pre>';print_r($_POST);exit;
            $adph = new Accountdeviceplanhistory();
            $adph->deviceID = strtoupper($_POST['GpsDevice']['deviceID']);
            $adph->accountID = $_POST['GpsDevice']['accountID'];
            $list = explode("#", $_POST['Accountdeviceplanhistory']['planName']);
            $adph->planID = $list[0];
            $adph->planName = $list[1];
            $adph->planAmount = $list[2];
            $adph->amount = (int) $_POST[Accountdeviceplanhistory][amount];
            $adph->duration = $list[3];
            $update_on = $_POST['GpsDevice']['update_on'];
            $adph->startTime = $update_on;
            $adph->creationTime = date('Y-m-d h:i');
            $adph->expiryTime = date('Y-m-d', strtotime("+" . $list[3] . " months", strtotime($update_on)));
            $adph->save(false);
            $received = $adph->received == 0 ? "No" : "Yes";
            $content = "<tbody ><tr>
                            <td>" . $adph->planName . "</td>
                                <td>" . $adph->planAmount . "</td>
                                <td>" . $adph->amount . "</td>
                                    <td>" . $received . "</td>
                                        <td>" . $adph->startTime . "</td>
                            <td>" . $adph->expiryTime . "</td><td>" . $adph->creationTime . "</td><td > <a onclick='fnDeleteGpsPlan(" . $user[id] . ")' ><i class='delete-icon-block'></i></a> </td>";
            echo $content;
        }
    }

    public function actionCreate() {
        $model = new GpsDevice;
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['GpsDevice'];
            $model->vehicleID = $_POST['GpsDevice']['deviceID'];
            $model->uniqueID = $_POST['GpsDevice']['deviceID'];
            $model->displayName = $_POST['GpsDevice']['deviceID'];
            $model->description = $_POST['GpsDevice']['deviceID'];
            $model->statusCodeState = 61717;
            $model->ignitionIndex = 61717;
            $model->supportedEncodings = 7;
            $model->isActive = 1;
            $model->creationTime = time();
            $model->lastOdometerKM = 0;
            $model->lastDistanceKM = 0;
            $model->lastValidSpeedKPH = 0;
            $model->vehicleModel = $_POST['GpsDevice']['truckTypeId'];
            $model->deviceID = strtoupper($_POST['GpsDevice']['deviceID']);
            $tt = $model->vehicleModel;
            $truck = Trucktype::model()->findAll(array('select' => 'id_truck_type, title', 'condition' => 'id_truck_type=' . $tt));
            $model->vehicleModel = $truck[0]['title'];
            //echo '<pre>';print_r($_POST['GpsDevice']);exit;
            if ($model->vehicleType == "NTK") {
                $model->vehicleModel = $_POST['GpsDevice']['vehicleModel'];
            }
            $exp_installedBy = explode("@@", $_POST['GpsDevice']['installedBy']);
            $model->installedById = $exp_installedBy[0];
            $model->installedBy = $exp_installedBy[1];
            //echo '<pre>';print_r($model->attributes);exit;
            if ($model->validate()) {
                $model->save(false);
                //temporarly commented to add device fast,this should be uncom
                GpsDevice::model()->updateAll(array('lastOdometerKM' => 0), 'accountID="' . $_POST['GpsDevice']['accountID'] . '" and `lastOdometerKM` is NULL');
                //GpsEventData::model()->updateAll(array('odometerKM'=>0),'accountID!="" and `odometerKM` is NULL');

                GpsEventData::model()->updateAll(array("odometerKM" => 0), 'accountID="' . $_POST['GpsDevice']['accountID'] . '" and odometerKM is NULL');
                GpsEventData::model()->updateAll(array("distanceKM" => 0), 'accountID="' . $_POST['GpsDevice']['accountID'] . '" and distanceKM is NULL');

                GpsDevice::model()->updateAll(array("lastDistanceKM" => 0), 'accountID="' . $_POST['GpsDevice']['accountID'] . '" and lastDistanceKM is NULL');
                GpsDevice::model()->updateAll(array("lastOdometerKM" => 0), 'accountID="' . $_POST['GpsDevice']['accountID'] . '" and lastOdometerKM is NULL');

                Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
                $this->redirect('index');
            }
        }
        $this->render('create', array('model' => $model));
    }

    public function actionUpdate() {
        //$model=$this->loadModel($id);
        $pk = $_GET['ids']['deviceID'];
        //echo $aa;exit;
        $model = GpsDevice::model()->find("deviceID='$pk'");
        if (isset($_POST['GpsDevice'])) {
			//echo '<pre>';print_r($_POST);exit;
            $accModifyFlag = 0;
            $accountIDPrev = $model->accountID;
            if ($model->accountID != $_POST['GpsDevice']['accountID']) {
                $accModifyFlag = 1;
            }

            $devModifyFlag = 0;
            $deviceIDPrev = $model->deviceID;
            if ($model->deviceID != $_POST['GpsDevice']['deviceID']) {
                $devModifyFlag = 1;
            }

            $beforeAccountID = $model->accountID;
            $model->attributes = $_POST['GpsDevice'];
            $model->deviceID = strtoupper($_POST['GpsDevice']['deviceID']);
            $model->vehicleID = $_POST['GpsDevice']['deviceID'];
            $model->uniqueID = $_POST['GpsDevice']['deviceID'];
            $model->displayName = $_POST['GpsDevice']['deviceID'];
            $model->description = $_POST['GpsDevice']['deviceID'];
            $model->vehicleModel = $_POST['GpsDevice']['truckTypeId'];
            if ($beforeAccountID == 'santosh' || $beforeAccountID == 'accounts') {
                $model->lastDistanceKM = 0;
                $model->lastOdometerKM = 0;
                $model->creationTime = time();
            }

            $tt = $model->vehicleModel;
            $truck = Trucktype::model()->findAll(array('select' => 'id_truck_type, title', 'condition' => 'id_truck_type=' . $tt));
            $model->vehicleModel = $truck[0]['title'];
            //echo '<pre>';print_r($_POST['GpsDevice']);exit;
            if ($model->vehicleType == "NTK") {
                $model->vehicleModel = $_POST['GpsDevice']['vehicleModel'];
            }
            //$model->isActive =$_POST['GpsDevice']['isActive'];
            //echo '<pre>';print_r($model->isActive);exit;
            //if($_SESSION['id_admin_role']==1){
			if($_POST['GpsDevice']['installedBy']!=""){	
			$exp_installedBy = explode("@@", $_POST['GpsDevice']['installedBy']);
            $model->installedById = $exp_installedBy[0];
            $model->installedBy = $exp_installedBy[1];
			}
            //update devicePaymentStatus
			//echo '<pre>';print_r($_POST);//exit;
            if($model->devicePaymentStatus=="" && ($_POST['GpsDevice']['accountID']!='santosh' && $_POST['GpsDevice']['accountID']!='accounts' )){
				//exit("in if");
                $model->devicePaymentStatus="Pending";
            }else if($_POST['GpsDevice']['accountID']=='santosh' || $_POST['GpsDevice']['accountID']=='accounts'){
				//exit("in else");
                $model->devicePaymentStatus="";
            }
            //exit("outside");
            if ($model->validate()) {
                $model->save(false);
                if ($accModifyFlag || $devModifyFlag) {
                    //GpsEventData::model()->updateAll(array('accountID'=>$_POST['GpsDevice']['accountID'],'deviceID'=>$_POST['GpsDevice']['deviceID']),'deviceID="'.$deviceIDPrev.'"');
                    //exit("inside");
                }
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
                $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete() {//($id)
        $aa = Yii::app()->request->getParam('id');
        //$aa = Yii::app()->controller->id;    
        //echo '<pre>';print_r($aa);exit;
        $arrayRowId = $_GET['ids']['deviceID'];
        $arrayRowId = array("$arrayRowId");
        //$arrayRowId=  is_array(Yii::app()->request->getParam('id'))?Yii::app()->request->getParam('id'):array(Yii::app()->request->getParam('id'));
        //echo '<pre>';print_r($arrayRowId);exit;
        if (sizeof($arrayRowId) > 0) {
            $arrayRowId = $this->findRelated($arrayRowId);

            $criteria = new CDbCriteria;
            $criteria->addInCondition('deviceID', $arrayRowId);
            //echo '<pre>';print_r($arrayRowId);exit;               
            if (CActiveRecord::model('GpsDevice')->deleteAll($criteria)) {
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_delete_success'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
            }
        } else {
            Yii::app()->user->setFlash('alert', Yii::t('common', 'message_checkboxValidation_alert'));
            Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
        }

        if (!isset($_GET['ajax']))
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
    }

    public function findRelated($input) {
        $criteria = new CDbCriteria;
        $criteria->condition = 'deviceID IN ( ' . implode(",", $input) . ' )';

        return $input;
    }

    public function actionIndex() {
        //Yii::app()->db->enableProfiling=1;

        $model = new GpsDevice('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GpsDevice']))
            $model->attributes = $_GET['GpsDevice'];
        //echo '<pre>';print_r($model);exit;
        //$info['damaged']=  Yii::app()->db_gtps->createCommand("select count(*) from Device where isDamaged=1")->queryScalar();
        //$info['not working']=Yii::app()->db_gtps->createCommand("select count(*) from Device where ")->queryScalar();
        $this->render('index', array('info' => $info, 'model' => $model, 'dataSet' => $model->search()));
    }

    public function loadModel($id) {
        $model = Gpsdevice::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'country-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'devicePaymentStatus':
                    if($data->devicePaymentStatus!="" && $data->devicePaymentStatus!="Confirmed"){
                        $amount=Yii::app()->db_gts->createCommand("select amount from Accountdeviceplanhistory where deviceID='".$data->deviceID."' order by startTime asc limit 1")->queryScalar();
                        $return=$data->devicePaymentStatus."(".$amount.")";
                    }else{
                        $return=$data->devicePaymentStatus;
                    }
                break;
			case 'deviceID':
                $return = "<a href='#' onclick='getDeviceDetails(\"".$data->deviceID."\")'>".$data->deviceID."</a>";
                break;
            case 'vehicleType':
                $return = $this->vehicleType[$data->vehicleType];
                break;
            case 'creationTime':
                $timestamp = $data->creationTime + 19800;
                //$timestamp = gmdate("Y-m-d H:i:s", $timestamp);
                $timestamp = gmdate("d-M H:i", $timestamp);
                $return = $timestamp;
                break;
            case 'lastGPSTimestamp':
				if($data->lastGPSTimestamp){
                $timestamp = $data->lastGPSTimestamp + 19800;
                //$timestamp = gmdate("Y-m-d H:i:s", $t19800imestamp);
                $timestamp = gmdate("d-M H:i", $timestamp);
                $return = $timestamp;
				}else{
					$return = $data->lastGPSTimestamp;
				}
                break;
            /* case 'expiryTime':
              if($data->expiryTime!=""){
              $exp=explode("#",$data->expiryTime);
              $str=$exp[1]==1?"<span class='icon-right'></span>":"<span class='icon-close'></span>";
              $return=$exp[0]." ".$str;
              } */
            case 'expiryTime':
                if ($data->expiryTime != "") {
                    $exp = explode("#", $data->expiryTime);
                    if ($_SESSION['id_admin_role'] == 1) {
                        $str = $exp[1] == 1 ? "<span class='icon-right'></span>" : '<span onclick="fnupdate(\'' . $exp[2] . '\')" class="icon-close" id="' . $exp[2] . '"></span>';
                    } else {
                        $str = $exp[1] == 1 ? "<span class='icon-right'></span>" : '<span class="icon-close" id="' . $exp[2] . '"></span>';
                    }

                    $return = $exp[0] . " " . $str;
                }
                break;
        }
        return $return;
    }

}

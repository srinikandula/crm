<?php

class GPSapiController extends Controller {

    public $layout = "//layouts/guest";
    public $limit = 10;

    public function actions() {

        return array(
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function filters() {
        return array(
            'ajaxOnly + editable'
        );
    }

    /*public function actionGetAlerts() { //need to upload image
         
          //http://egcrm.cloudapp.net/operations/index.php/GPSapi/GetAlerts
          //method=get
          //param=offset,accountid
          
        $json = array("status" => 0);
        $offset = (int) $_REQUEST['offset'];
        //if (Yii::app()->request->isPostRequest) {
        if (1) {

            $count = Yii::app()->db->createCommand("select count(*) from {{gps_alerts}}")->queryScalar();
            $rows = Yii::app()->db->CreateCommand("select * from {{gps_alerts}} order by date_created desc limit " . $this->limit . " offset " . $offset)->queryAll();
            $json['status'] = 1;
            $json['data'] = $rows;
            $json['count'] = $count;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }*/

	    public function actionGetAlerts() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapi/GetAlerts
          method=post
          param=accountid
         */
        $json = array("status" => 0);
        $accountid =  $_REQUEST['accountid'];
        //if (Yii::app()->request->isPostRequest) {
        $rows=array();
        if (1) {
            $rows[0] = Yii::app()->db->CreateCommand("select * from eg_gps_alerts where sendtoall=1 and date(date_created)+ INTERVAL 30 DAY >=NOW() order by date_created desc")->queryAll();
            $rows[1] = Yii::app()->db->CreateCommand("select ga.* from eg_gps_alerts ga,eg_gps_alerts_users gau where ga.id_gps_alerts=gau.id_gps_alerts and (gau.gps_account_id='".$accountid."' or gau.id_customer_mobile='".$accountid."' ) and ga.sendtoall=0 and date(ga.date_created)+ INTERVAL 30 DAY >=NOW() order by ga.date_created desc")->queryAll();
            $rows=array_merge($rows[0],$rows[1]);
            //echo '<pre>';print_r($rows);
            $json['status'] = 1;
            $json['data'] = $rows;
            //$json['count'] = $count;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionSetTruckInfo() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapi/SetTruckInfo
          method=post
          params=accountid,address,truck_reg_no,date_available,mobile
        */

        $json = array("status" => 0);
        $accountid = $_REQUEST['accountid'];
        $address = $_REQUEST['address'];
        $truck_reg_no = $_REQUEST['truck_reg_no'];
        $mobile = $_REQUEST['mobile'];
        $date_available = $_REQUEST['date_available'];
        //if (Yii::app()->request->isPostRequest) {
		if(1){
            $json['status'] = 1;
            $model = new Gpstrucklocation;
            $model->accountid = $accountid;
            $model->address = $address;
            $model->truck_reg_no = $truck_reg_no;
            $model->mobile = $mobile;
            $model->date_available = $date_available;
            $model->save(false);
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function actionGetTrucks() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapi/GetTrucks
          method=post
          params=accountid
        */

        $json = array("status" => 0);
        $accountid = $_REQUEST['accountid'];
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            $json['status'] = 1;
            //$trRows=Yii::app()->db->createCommand("select replace(trim(tr.truck_reg_no),' ','') as truck_reg_no from {{customer}} c,{{truck}} tr where c.id_customer=tr.id_customer and c.mobile='".$accountid."' and tracking_available=0")->queryAll();
			
            $trRows=Yii::app()->db->createCommand("select distinct replace(trim(tr.truck_reg_no),' ','') as truck_reg_no from eg_customer c,eg_truck tr where c.id_customer=tr.id_customer and (c.gps_account_id='".$accountid."' or c.mobile='".$accountid."') and tracking_available=0")->queryAll();
            if(count($trRows)){
                foreach($trRows as $trRow){
                    $json['data'][]=$trRow['truck_reg_no'];
                }
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionLogin() {
        
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapi/Login
          method=post
          params=userName,password
        */
        
        $json = array("statusCode" => 0);
        $userName = $_REQUEST['userName'];
        $password = $_REQUEST['password'];
		$deviceid = $_REQUEST['deviceid'];
        //if (Yii::app()->request->isPostRequest && $userName!="" && $password!="") {
        if (1) {
            $gpsRow = Yii::app()->db_gts->createCommand("select accountID,contactName,contactPhone,contactEmail from Account where accountID='" . $userName . "' and password='" . $password . "' and isActive=1")->queryRow();
            //print_r($gpsRow);
            // exit("value of ".$gpsRow['accoundID']);
            if ($gpsRow['accountID'] != "") {
                $json['statusCode'] = 1;
                $json['success'] = array('accountID' => $gpsRow['accountID'], 'contactName' => $gpsRow['contactName'], 'contactPhone' => $gpsRow['contactPhone'], 'contactEmail' => $gpsRow['contactEmail'], 'gps' => 1, 'truck' => 1);
				//$this->addLoginDevice(array('username'=>$userName,'deviceid'=>$deviceid));
			} else {
                $user = Customer::model()->find('status=1 and type="T" and mobile=?', array(strtolower($userName)));
				//truck owners only
                if ($user === null) {
                    $json['statusCode'] = 0;
                    $json['error'][] = array('msg' => "Invalid Username/Password.", 'value' => array("userName" => $userName));
                } else if (!$user->validatePassword($password)) {
                    $json['statusCode'] = 0;
                    $json['error'][] = array('msg' => "Invalid Username/Password.", 'value' => array("userName" => $userName));
                } else {
                    $json['statusCode'] = 1;
                    $json['success'] = array('accountID' => $user->mobile, 'contactName' => $user->fullname, 'contactPhone' => $user->alt_mobile_1, 'contactEmail' => $user->email, 'gps' => 0, 'truck' => 1);
	                //$this->addLoginDevice(array('username'=>$userName,'deviceid'=>$deviceid));
				}
            }
        }
        //echo '<pre>';print_r($json);echo '</pre>';
        /*
          {"statusCode":1,"success":{"accountID":"santosh","contactName":"santosh","contactPhone":"9035928271","contactEmail":"arjun@easygaadi.com","vehicleType":"TRUCK"}}

          {"statusCode":0,"error":[{"msg":"Invalid Username/Password.","value":{"userName":"santosh"}}]}
         */
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	function addLoginDevice($data){
        if($data['deviceid']!=""){
            Yii::app()->db->createCommand("delete from  eg_gps_login_device where username like '".$data['username']."'")->query();
            Yii::app()->db->createCommand("insert into eg_gps_login_device(username,device_id) values('".$data['username']."','".$data['deviceid']."')")->query();
        }
    }

	    	    public function actionSetTruck() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapi/SetTruck
          method=post
          params=username,gps,truck_reg_no,id_truck_type //remove id send username and gps 0,1
          status=0,1
          when 1 or 0 will check if account exists if not will create account with gpsid in it and add trucks
        */

        $json = array("status" => 0);
        //$id = $_REQUEST['id'];
        $username = $_REQUEST['username'];
        $gps = (int)$_REQUEST['gps'];
        $truck_reg_no = $_REQUEST['truck_reg_no'];
        $id_truck_type = (int)$_REQUEST['id_truck_type'];
        //if (Yii::app()->request->isPostRequest) {
        if ($truck_reg_no && $truck_reg_no!="") {
            if($gps){ //gps account
               $rowId=Yii::app()->db->createCommand("select id_customer from eg_customer where gps_account_id='".$username."'")->queryScalar(); 
               if($rowId==""){//create crm customer account
                   $gtsRow=Yii::app()->db_gts->createCommand("select contactPhone,contactName,password where accountID='".$username."'")->queryRow();
                   $rowId=$this->addCustomer(array('fullname'=>$gtsRow['contactName'],'mobile'=>$gtsRow['contactPhone'],'password'=>$gtsRow['password']));
                   Customer::model()->updateAll(array('gps_account_id'=>$username),'id_customer="'.$rowId.'"');
               }
            }else{
                $rowId=Yii::app()->db->createCommand("select id_customer from eg_customer where mobile='".$username."'")->queryScalar(); 
            }
            $truckObj=new Truck;
            $truckObj->truck_reg_no=$truck_reg_no;
            $truckObj->id_truck_type=$id_truck_type;
            $truckObj->id_customer=$rowId;
            $truckObj->date_created=new CDbExpression('NOW()');
            $truckObj->save(false);
            $json['status']=1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function actionSetCustomer() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapi/SetCustomer
          method=post
          params=fullname,mobile,address
          status=0,1,-1
          1:account created successfully
          0:account creation failed
         -1:mobile already exists
        */

        $json = array("status" => 0);
        $fullname = $_REQUEST['fullname'];
        $mobile = $_REQUEST['mobile'];
        $address = $_REQUEST['address'];
        //if (Yii::app()->request->isPostRequest) {
        if ($mobile!="" && $fullname!="" && $address!="") {
			$count=Yii::app()->db->createCommand("select count(*) as count from eg_customer where mobile='".$mobile."' and type!='G'")->queryScalar();
			if(!$count){
                                $password=Library::randomPassword();
                                $this->addCustomer(array('fullname'=>$fullname,'mobile'=>$mobile,'password'=>$password,'address'=>$address));
								//exit($mobile." mobile and password ".$password);
				Library::sendSingleSms(array('to'=>$mobile,'message'=>"password:".$password));
				$json['status']=1;
			}else{
				$json['status']='-1';
			}
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function addCustomer($input){
            $CustObj=new Customer;           
            $CustObj->password = Admin::hashPassword($input['password']);
            $CustObj->islead=1;
            $CustObj->type='T';
            $CustObj->fullname=$input['fullname'];
            $CustObj->mobile=$input['mobile'];
            $CustObj->address=$input['address'];
            $CustObj->status=1;
            $CustObj->date_created=new CDbExpression('NOW()');
            $CustObj->save(false);

            $CustleadObj=new Customerlead;
            $CustleadObj->id_customer=$CustObj->id_customer;
            $CustleadObj->lead_status='Initiated';
            $CustleadObj->lead_source='Truck App';
            $CustleadObj->save(false);
            return $CustObj->id_customer;
    } 
    
    public function actionSetLoad() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapi/SetLoad
          method=post
          params=id_gps_alert,accountid,price
          status=0,1
          1:success
          0:faile
        */

        $json = array("status" => 1);
        $id = $_REQUEST['id_gps_alert'];
        $accountid = $_REQUEST['accountid'];
        $price = $_REQUEST['price'];
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            //Gpsalertsinterested::model()->deleteAll('');
            $obj=new Gpsalertsinterested();
            $obj->id_gps_alert=$id;
            $obj->expected_price=$price;
            $obj->account_id=$accountid;
            $obj->save(false);
            $json['status']=1;    
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actiontestsms(){
		//Library::sendSingleSms(array('to'=>'9848750094','message'=>"password:"));
	}

	public function actionisUserActive(){
		/*
          http://egcrm.cloudapp.net/operations/index.php/GPSapi/isUserActive
          method=post
          params=username
          status=0,1
          1:success
          0:faile
        */
        $json=array('status'=>1);
        $id_admin=(int)$_REQUEST['id_admin'];
        //if (Yii::app()->request->isPostRequest) {
		/*if (1) {
		$row=Admin::model()->find('id_admin='.$id_admin);
        if($row->status){
            $json['status']=1;
        }
		}*/
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	    public function actionGetGoodsType(){
			//http://egcrm.cloudapp.net/operations/index.php/GPSapi/GetGoodsType
        $rows=Yii::app()->db->createCommand('select id_goods_type,title from {{goods_type}} where status=1')->queryAll();
        $return=array();
        foreach($rows as $row){
            $return[]=array('Key'=>$row['id_goods_type'],'Value'=>$row['title']);
        }
        echo CJSON::encode($return);
        Yii::app()->end();
    }

	public function actiontrackallvehicles() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapi/trackallvehicles
          method=post
          params=account_id
        */header('Content-Type: application/json');
            $account_id=$_REQUEST['account_id'];    
            $json=array();
            $json['statusCode']=0;

            //if(($account_id!="") && (Yii::app()->request->isPostRequest)){
            if($account_id!=""){
                
				if($account_id=="egmumbai"){
					//mumbai surroundinb vehicles around 150km radius
                    $pointLat='18.864474444444443';
                    $pointLng= '73.20685444444445';
                    $radius=150;
                    $query="SELECT deviceID as truck_no,deviceID,ifnull(lastValidLatitude,0) as latitude,ifnull(lastValidLongitude,0) as longitude,concat(ceil(lastValidSpeedKPH*1.852),' ','Km/Hr') as speed, (ifnull(lastUpdateTime,0)+19800) as time_in_secs,FROM_UNIXTIME(ifnull(lastUpdateTime,0), '%Y %D %M %h:%i') as date_time,concat(ceil(lastOdometerKM),' ','Km') as odometer, ( 3959 * acos( cos( radians(".$pointLat.") ) * cos( radians(lastValidLatitude) ) * cos( radians(lastValidLongitude) - radians(".$pointLng.") ) + sin( radians(".$pointLat.") ) * sin( radians(lastValidLatitude) ) ) ) AS distance FROM Device HAVING distance < ".$radius." ORDER BY distance";    

                }else if($account_id=="egattached"){
						$query = "select deviceID as truck_no,deviceID,lastValidLatitude as latitude,lastValidLongitude as longitude,concat(ceil(lastValidSpeedKPH*1.852),' ','Km/Hr') as speed, (ifnull(lastUpdateTime,0)+19800) as time_in_secs,FROM_UNIXTIME(ifnull(lastUpdateTime,0), '%Y %D %M %h:%i') as date_time,concat(ceil(ifnull(lastOdometerKM,0)),' ','Km') as odometer from Device where deviceID in (select deviceID from AttachedDevice) order by lastValidSpeedKPH desc";                        

                }else{

						$query = "select deviceID as truck_no,deviceID,ifnull(lastValidLatitude,0) as latitude,ifnull(lastValidLongitude,0) as longitude,concat(ceil(lastValidSpeedKPH*1.852),' ','Km/Hr') as speed, (ifnull(lastUpdateTime,0)+19800) as time_in_secs,FROM_UNIXTIME(ifnull(lastUpdateTime,0), '%Y %D %M %h:%i') as date_time,concat(ceil(ifnull(lastOdometerKM,0)),' ','Km') as odometer from Device where accountID = '".$account_id."'  order by lastValidSpeedKPH desc";
                }

                $rows=Yii::app()->db_gts->createCommand($query)->queryAll();
                if(is_array($rows) && sizeof($rows)){
                        $json['statusCode']="1";
						
					$acctDetails=Yii::app()->db_gts->createCommand("select contactPhone,contactName from Account where accountID='".$account_id."'")->queryRow();
					$i=0;
					foreach($rows as $row){
						$success[$i]=$row;
						//$addr=Library::getGPBYLATLNGDetails($row[latitude].",".$row[longitude]);
						//$addr=Library::getGPBYLATLNGDetailsForMobile($row[latitude].",".$row[longitude]);
						
						$success[$i]['address']=substr(str_replace(', ',',',$addr[address]),0,-13);
						$success[$i]['truck_owner']=$acctDetails['contactName'];
						$success[$i]['truck_owner_phone']=$acctDetails['contactPhone'];
					$i++;	
					}
                    $json['success']=$success;
                }else{
                    $json['error'][]=array("msg"=>"No Device Found");
                }
            }else{
                $json['error'][]=array("msg"=>"No Device Found");
            }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
}
<?php

class GPSapiV1Controller extends Controller {

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
         
          //http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/GetAlerts
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
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/GetAlerts
          method=post
          param=accountid
         new param uid
         */
        $json = array("status" => 0);
        $accountid =  $_REQUEST['accountid'];
        $uid =  $_REQUEST['uid'];
		$type =  $_REQUEST['type'];
        //if (Yii::app()->request->isPostRequest) {
        $rows=array();
        if (1) {
			if($type=='T'){
				$select="ga.*";
			}else if($type=='C'){
				$select="ga.id_gps_alerts,ga.source,ga.destination,ga.id_truck_type_title,ga.price";
			}else{
				$select="ga.*";
			}

            $rows[0] = Yii::app()->db->CreateCommand("select ".$select." from eg_gps_alerts ga where ga.sendtoall=1 and date(ga.date_created)+ INTERVAL 30 DAY >=NOW() order by ga.date_created desc")->queryAll();
            /*$rows[1] = Yii::app()->db->CreateCommand("select ga.* from eg_gps_alerts ga,eg_gps_alerts_users gau where ga.id_gps_alerts=gau.id_gps_alerts and (gau.gps_account_id='".$accountid."' or gau.id_customer_mobile='".$accountid."' ) and ga.sendtoall=0 and date(ga.date_created)+ INTERVAL 30 DAY >=NOW() order by ga.date_created desc")->queryAll();*/
            $rows[1] = Yii::app()->db->CreateCommand("select ".$select." from eg_gps_alerts ga,eg_gps_alerts_users gau where ga.id_gps_alerts=gau.id_gps_alerts and (gau.id_customer='".$uid."') and ga.sendtoall=0 and date(ga.date_created)+ INTERVAL 30 DAY >=NOW() order by ga.date_created desc")->queryAll();
            
            $rows=array_merge($rows[0],$rows[1]);
            //echo '<pre>';print_r($rows);
            $json['status'] = 1;
			foreach($rows as $row){
				$json['data'][] = array("id_goods_type"=>$row[id_goods_type]==""?"":$row[id_goods_type],
					"id_truck_type"=>$row[id_truck_type]==""?"":$row[id_truck_type],
					"id_gps_alerts"=>$row[id_gps_alerts]==""?"":$row[id_gps_alerts],
					"notified"=>$row[notified]==""?"":$row[notified],
					"sendtoall"=>$row[sendtoall]==""?"":$row[sendtoall],
					"date_required"=>$row[date_required]==""?"":$row[date_required],
					"date_created"=>$row[date_created]==""?"":$row[date_created],
					"accountid"=>$row[accountid]==""?"":$row[accountid],
					"message"=>$row[message]==""?"":$row[message],
					"price"=>$row[price]==""?"":$row[price],
					"id_goods_type_title"=>$row[id_goods_type_title]==""?"":$row[id_goods_type_title],
					"source"=>$row[source]==""?"":$row[source],
					"destination"=>$row[destination]==""?"":$row[destination],
					"id_truck_type_title"=>$row[id_truck_type_title]==""?"":$row[id_truck_type_title],
					"id_goods_type_title"=>$row[id_goods_type_title]==""?"":$row[id_goods_type_title]
					);
			}
            //$json['count'] = $count;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionSetTruckInfo() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/SetTruckInfo
          method=post
          params=accountid,address,truck_reg_no,date_available,mobile
          
         add param uid
        */

        $json = array("status" => 0);
        $accountid = $_REQUEST['accountid'];
        $uid = $_REQUEST['uid'];
        $address = $_REQUEST['address'];
        $truck_reg_no = $_REQUEST['truck_reg_no'];
        $mobile = $_REQUEST['mobile'];
        $date_available = $_REQUEST['date_available'];
        //if (Yii::app()->request->isPostRequest) {
		if(1){
            $json['status'] = 1;
            $model = new Gpstrucklocation;
            $model->accountid = $accountid;
            $model->id_customer = $uid;
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
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/GetTrucks
          method=post
          params=accountid
         modified
         uid
        */

        $json = array("status" => 0);
        //$accountid = $_REQUEST['accountid'];
        //if (Yii::app()->request->isPostRequest) {
        $uid = $_REQUEST['uid'];
        if (1) {
            $json['status'] = 1;
            $trRows=Yii::app()->db->createCommand("select distinct replace(trim(tr.truck_reg_no),' ','') as truck_reg_no,tr.id_truck from eg_truck tr where tr.id_customer='".$uid."' and tracking_available=0")->queryAll();
            $json['data']=$trRows;
			
			/*if(count($trRows)){
                foreach($trRows as $trRow){
                    $json['data'][]=$trRow['truck_reg_no'];
                }
            }*/
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	

    public function actionLogin() {
        
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/Login
          method=post
          params=userName,password
        */
        
        $json = array("statusCode" => 0);
        $userName = $_REQUEST['userName'];
        $password = $_REQUEST['password'];
		$deviceid = $_REQUEST['deviceid'];
        //if (Yii::app()->request->isPostRequest && $userName!="" && $password!="") {
        if (1){
                
                $user = Customer::model()->find('islead=0 and status=1 and (type="L" or type="T" or type="TR" or type="C")  and (mobile="'.$userName.'" or gps_account_id="'.$userName.'")');
				//truck owners only
                if ($user === null) {
                    $json['statusCode'] = 0;
                    $json['error'][] = array('msg' => "Invalid Username/Password.", 'value' => array("userName" => $userName));
                } else if (!$user->validatePassword($password)) {
                    $json['statusCode'] = 0;
                    $json['error'][] = array('msg' => "Invalid Username/Password..", 'value' => array("userName" => $userName));
                } else {
                    $json['statusCode'] = 1;
                    $json['success']['uid']=$user->id_customer; 
					$json['success']['type']=$user->type;
                    $json['success']['accountID']=$user->gps_account_id==""?$user->mobile:$user->gps_account_id; 
                    $json['success']['contactName']=$user->fullname;
                    $json['success']['contactPhone']=$user->mobile;
                    $json['success']['contactEmail']=$user->email;
                    if($user->type=='T'){
                        $json['success']['gps']=$user->gps_account_id==""?0:1; //gps account exists then 1
                        $json['success']['truck']=1;
                        $json['success']['loads']=1;
                        $json['success']['postload']=0;
                        $json['success']['loadstatus']=0;
                        $json['success']['orders']=1;
                        $json['success']['truckavailable']=0;
                    }else if($user->type=='TR'){
                        $json['success']['gps']=$user->gps_account_id==""?0:1; //gps account exists then 1
                        $json['success']['truck']=0;
                        $json['success']['loads']=0;
                        $json['success']['postload']=1;
                        $json['success']['loadstatus']=1;
                        $json['success']['orders']=1;
                        $json['success']['truckavailable']=1;
                    }else if($user->type=='C'){
                        $json['success']['gps']=$user->gps_account_id==""?0:1; //gps account exists then 1
                        $json['success']['truck']=1;
                        $json['success']['loads']=1;
                        $json['success']['postload']=0;
                        $json['success']['loadstatus']=0;
                        $json['success']['orders']=0;
                        $json['success']['truckavailable']=0;
                    }
                    
                    /*= array('accountID' => $user->mobile, 'contactName' => $user->fullname, 'contactPhone' => $user->alt_mobile_1, 'contactEmail' => $user->email, 'gps' => 0, 'truck' => 1);*/
	                $this->addLoginDevice(array('username'=>$userName,'deviceid'=>$deviceid));
				}
            }
        
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	function addLoginDevice($data){
        if($data['deviceid']!=""){
            //Yii::app()->db->createCommand("delete from  eg_gps_login_device where username like '".$data['username']."'")->query();
            Yii::app()->db->createCommand("insert into eg_gps_login_device(username,device_id) values('".$data['username']."','".$data['deviceid']."')")->query();
        }
    }

	public function actionSetTruck() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/SetTruck
          method=post
          params=username,gps,truck_reg_no,id_truck_type //remove id send username and gps 0,1
          status=0,1
          when 1 or 0 will check if account exists if not will create account with gpsid in it and add trucks
          modified params
          truck_reg_no,id_truck_type,uid
        */

        $json = array("status" => 0);
        //$truck_reg_no = $_REQUEST['truck_reg_no'];
		$truck_reg_no = strtolower(str_replace(" ", "", $_REQUEST['truck_reg_no']));
        $id_truck_type = (int)$_REQUEST['id_truck_type'];
        $uid = (int)$_REQUEST['uid'];
        if ($truck_reg_no && $truck_reg_no!="") {
            $truckObj=new Truck;
            $truckObj->truck_reg_no=$truck_reg_no;
            $truckObj->id_truck_type=$id_truck_type;
            $truckObj->id_customer=$uid;
            $truckObj->date_created=new CDbExpression('NOW()');
            $truckObj->save(false);
            $json['status']=1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function actionSetCustomer() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/SetCustomer
          method=post
          params=fullname,mobile,address,type (T,TR,C)
          status=0,1,-1
          1:account created successfully
          0:account creation failed
         -1:mobile already exists
        */

        $json = array("status" => 0);
        $fullname = $_REQUEST['fullname'];
        $mobile = $_REQUEST['mobile'];
        $address = $_REQUEST['address'];
        $type = $_REQUEST['type'];
        //if (Yii::app()->request->isPostRequest) {
        if ($mobile!="" && $fullname!="" && $address!="") {
			$count=Yii::app()->db->createCommand("select count(*) as count from eg_customer where mobile='".$mobile."' and type!='G'")->queryScalar();
			if(!$count){
                                $password=Library::randomPassword();
                                $this->addCustomer(array('status'=>0,'type'=>$type,'fullname'=>$fullname,'mobile'=>$mobile,'password'=>$password,'address'=>$address));
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
            $CustObj->type=$input['type']==''?'T':$input['type'];//'T';
            $CustObj->fullname=$input['fullname'];
            $CustObj->mobile=$input['mobile'];
            $CustObj->address=$input['address'];
            $CustObj->status=$input['status'];
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
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/SetLoad
          method=post
          params=id_gps_alert,accountid,price
          status=0,1
          1:success
          0:faile
         new param uid
        */

        $json = array("status" => 1);
        $id = $_REQUEST['id_gps_alert'];
        $accountid = $_REQUEST['accountid'];
        $uid = $_REQUEST['uid'];
        $price = $_REQUEST['price'];
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            //Gpsalertsinterested::model()->deleteAll('');
            $obj=new Gpsalertsinterested();
            $obj->id_gps_alert=$id;
            $obj->expected_price=$price;
            $obj->account_id=$accountid;
            $obj->id_customer=$uid;
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
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/isUserActive
          method=post
          params=username,uid
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
    
    public function actionGetOrders() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/GetOrders
          method=post
          params=uid,type,offset
         modified
         uid
        */

        $json = array("status" => 0);
        //$accountid = $_REQUEST['accountid'];
        //if (Yii::app()->request->isPostRequest) {
        $uid =$_REQUEST['uid'];
        $type = $_REQUEST['type'];
        $offset = (int)$_REQUEST['offset'];
        if (1) {
            $json['status'] = 1;
            $trRows=Yii::app()->db->createCommand("select truck_reg_no,truck_type,goods_type,order_status_name,id_order,source_city,destination_city,date_ordered from eg_order where (id_customer='".$uid."' or id_customer_ordered='".$uid."') order by date_ordered desc limit ".$this->limit." offset ".$offset)->queryAll();
			$total=Yii::app()->db->createCommand("select count(*) from eg_order where (id_customer='".$uid."' or id_customer_ordered='".$uid."')")->queryScalar();
            $json['count']=$total;
			if(count($trRows)){
                //$json['data']=$trRows;
				foreach($trRows as $trRow){
				//$date_future=strtotime($trRow['date_ordered'])+86400;
				//echo "select  count(*) from Device where (creationTime<'".strtotime($trRow['date_ordered'])."') and deviceID='".strtolower(str_replace(' ','',$trRow['truck_reg_no']))."'";
				$track=Yii::app()->db_gts->createCommand("select  count(*) from Device where deviceID='".strtolower(str_replace(' ','',$trRow['truck_reg_no']))."'")->queryScalar();
                $json['data'][]=array("current_location"=>"Hyderabad","order_amount"=>"2100","pending_amount"=>"1500","tracking"=>$track,"truck_reg_no"=>$trRow['truck_reg_no'],"truck_type"=>$trRow['truck_type'],"goods_type"=>$trRow['goods_type'],"order_status_name"=>$trRow['order_status_name'],"id_order"=>$trRow['id_order'],"source_city"=>$trRow['source_city'],"destination_city"=>$trRow['destination_city'],"date_ordered"=>$trRow['date_ordered']);
                }
            }else{
				$json['data']=array();
				}
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
        public function actionSetPostLoad() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/SetPostLoad
          method=post
          params=uid,source_address,destination_address,pickup_point,expected_price,comment,tracking,id_truck_type,id_goods_type,date_required
        */

        $json = array("status" => 0);
        $data=$_REQUEST;
        //if (Yii::app()->request->isPostRequest) {
        $uid = $_REQUEST['uid'];
        if (1) {
            
                $model=new Loadtruckrequest();
                $src = Library::getGPDetails($data['source_address']);
                $dest = Library::getGPDetails($data['destination_address']);
                $row = Admin::model()->getLeastAssigmentIdSearch();
                $custObj=Customer::model()->find('id_customer="'.$uid.'"');		
                $model->id_customer = $uid;
                $model->id_admin_assigned = $row['id_admin'];
                $model->title = $custObj->idprefix.",".$custObj->fullname.",".Library::getCustomerType($custObj->type).",".$custObj->mobile.",".$custObj->email;
                $model->pickup_point = $data['pickup_point'];
                $model->source_address = trim($src['address']) == "" ? trim($src['input']) : trim($src['address']);
                $model->source_state = trim($src['state']);
                $model->source_city = trim($src['city']) == "" ? trim($src['input']) : trim($src['city']);
                $model->source_lat = trim($src['lat']);
                $model->source_lng = trim($src['lng']);
                $model->destination_address = trim($dest['address']) == "" ? trim($dest['input']) : trim($dest['address']);
                $model->destination_state = trim($dest['state']);
                $model->destination_city = trim($dest['city']) == "" ? trim($dest['input']) : trim($dest['city']);
                $model->destination_lat = trim($dest['lat']);
                $model->destination_lng = trim($dest['lng']);
                $model->expected_price = $data['expected_price'];
                $model->status = 'New';
                $model->approved = 1;
                $model->isactive = 1;
				$model->loading_charge = $data['loading_charge'];
                $model->unloading_charge = $data['unloading_charge'];
                $model->comment = $data['comment'];
                $model->tracking = $data['tracking'];
                $model->insurance = $data['insurance'];
                $model->id_truck_type = $data['id_truck_type'];
                $model->id_goods_type = $data['id_goods_type'];
                $model->date_required = trim($data['date_required']);
                $model->date_created = new CDbExpression('NOW()');
                if($model->validate()){
                    $model->save(false);
                    $json['status'] = 1;
                }else {
					$json['errors']=$model->getErrors();
				}
				//echo '<pre>';print_r($model->getErrors());echo '</pre>';
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function actionGetPostLoads() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/GetPostLoads
          method=post
          params=uid,offset
        */

        $json = array("status" => 0);
        //$accountid = $_REQUEST['accountid'];
        //if (Yii::app()->request->isPostRequest) {
        $uid = $_REQUEST['uid'];
        $offset = (int)$_REQUEST['offset'];
        if (1) {
            $json['status'] = 1;
			$total=Yii::app()->db->createCommand("select count(*) as total from eg_load_truck_request ltr where ltr.id_customer='".$uid."' and  ltr.isactive=1 order by  ltr.id_load_truck_request")->queryScalar();
			$json['count']=(int)$total;
            $trRows=Yii::app()->db->createCommand("select (select count(*) as count from eg_load_truck_request_quotes ltrq where ltrq.id_load_truck_request=ltr.id_load_truck_request) as quotecount,status,tracking,comment,(select gt.title from eg_goods_type gt where gt.id_goods_type=ltr.id_goods_type) as goods_type,(select tt.title from eg_truck_type tt where tt.id_truck_type=ltr.id_truck_type) as truck_type,ltr.id_load_truck_request,ltr.expected_price,ltr.date_required,ltr.id_goods_type,ltr.id_truck_type,ltr.pickup_point,ltr.comment,ltr.source_city,ltr.destination_city from eg_load_truck_request ltr where ltr.id_customer='".$uid."' and  ltr.isactive=1 order by  ltr.id_load_truck_request desc limit ".$this->limit." offset ".$offset)->queryAll();
            if(count($trRows)){
                foreach($trRows as $trRow){
                    //$trRow['quotes'][]=array('id'=>123,'cid'=>'C123','quote'=>1200,'message'=>'ready to move','booking_request'=>0);
                    $json['data'][]=$trRow;
                }
                
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
 
    
        public function actionGetQuotes() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/GetQuotes
          method=post
          params=lid
        */

        $json = array("status" => 0);
        //$accountid = $_REQUEST['accountid'];
        //if (Yii::app()->request->isPostRequest) {
        $lid = (int)$_REQUEST['lid'];
        if (1) {
            $json['status'] = 1;
			//echo "select c.idprefix,ltrq.* from eg_customer c,eg_load_truck_request_quotes ltrq where c.id_customer=ltrq.id_customer and ltrq.id_load_truck_request='".$lid."'";
            //$rows=Loadtruckrequestquotes::model()->findAll('id_load_truck_request="'.$lid.'"');
            $rows=Yii::app()->db->createCommand("select c.idprefix,ltrq.* from eg_customer c,eg_load_truck_request_quotes ltrq where c.id_customer=ltrq.id_customer and ltrq.id_load_truck_request='".$lid."'")->queryAll();
            foreach($rows as $row){
                $json['data'][]=array('qid'=>$row['id_load_truck_request_quotes'],'cid'=>$row['idprefix'],'quote'=>$row['quote'],'message'=>$row['message'],'booking_request'=>$row['booking_request']);
            }
            //$json['data'][]=array('qid'=>123,'cid'=>'C123','quote'=>1200,'message'=>'ready to move','booking_request'=>0);
         }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
	public function actionselectLoadQuote() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/selectLoadQuote
          method=post
          params=qid
        */

        $json = array("status" => 0);
        //if (Yii::app()->request->isPostRequest) {
        $qid = $_REQUEST['qid'];
        $offset = (int)$_REQUEST['offset'];
        if (1) {
            $json['status'] = 1;
            Loadtruckrequestquotes::model()->updateAll(array('booking_request'=>1),'id_load_truck_request_quotes="'.$qid.'"');
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }


    
    public function actionselectTrucksAvailable() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/selectTrucksAvailable
          method=post
         uid,tid
        */

        $json = array("status" => 0);
        //if (Yii::app()->request->isPostRequest) {
        $uid = (int)$_REQUEST['uid'];
        $tid = (int)$_REQUEST['tid'];
        if (1) {
            Truckloadrequestinterested::model()->deleteAll('id_customer="'.$uid.'" and id_truck_load_request="'.$tid.'"');
            $json['status'] = 1;
            $model=new Truckloadrequestinterested;
            $model->id_customer=$uid;
            $model->id_truck_load_request=$tid;
            $model->save(false);
			$json['tid']=$tid;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actionDeleteTruck(){
	/*
        http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/DeleteTruck
        method=post
        tid
        */

        $json = array("status" => 0);
        $tid = (int)$_REQUEST['tid'];
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            $json['status'] = 1;
            Truck::model()->deleteAll('id_truck="'.$tid.'"');
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actionCancelPostedLoad(){
	/*
        http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/CancelPostedLoad
        method=post
        lid
        */

        $json = array("status" => 0);
        $lid = (int)$_REQUEST['lid'];
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            $json['status'] = 1;
            Loadtruckrequest::model()->updateAll(array('isactive'=>0),'id_load_truck_request="'.$lid.'"');
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actiongetTrucksAvailable1() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/getTrucksAvailable
          method=post
        */

        $json = array("status" => 0);
        //if (Yii::app()->request->isPostRequest) {
        $offset = (int)$_REQUEST['offset'];
		$uid = (int)$_REQUEST['uid'];
        if (1) {
            $json['status'] = 1;
			/*echo "select *,(select tt.title from eg_truck_type tt where tt.id_truck_type=tlr.id_truck_type) as truck_type,(select gt.title from eg_goods_type gt where gt.id_goods_type=tlr.id_goods_type) as goods_type,(select count(*) from eg_truck_load_request_interested tlri where tlri.id_truck_load_request=tlr.id_truck_load_request and tlri.id_customer='".$uid."') as interested from eg_truck_load_request tlr where tlr.status=1 and date(tlr.date_available)>=date(Now()) order by tlr.date_available asc";exit;*/
			$total=Yii::app()->db->createCommand("select count(*) as total from eg_truck_load_request tlr,eg_truck_load_request_destinations tlrd where tlr.id_truck_load_request=tlrd.id_truck_load_request and tlr.status=1 and date(tlr.date_available)<date(Now())")->queryScalar(); //>=
			$json['count'] = (int)$total;
			$rows=Yii::app()->db->createCommand("select *,tlrd.price,tlrd.destination_city,(select tt.title from eg_truck_type tt where tt.id_truck_type=tlr.id_truck_type) as truck_type,(select gt.title from eg_goods_type gt where gt.id_goods_type=tlr.id_goods_type) as goods_type,(select count(*) from eg_truck_load_request_interested tlri where tlri.id_truck_load_request=tlr.id_truck_load_request and tlri.id_customer='".$uid."') as interested from eg_truck_load_request tlr,eg_truck_load_request_destinations tlrd where tlr.id_truck_load_request=tlrd.id_truck_load_request and tlr.status=1 and date(tlr.date_available)<date(Now()) order by tlr.date_available asc limit " . $this->limit . " offset " . $offset)->queryAll();

			foreach($rows as $row){
				$json['data'][]=array("tid"=>$row['id_truck_load_request'],"tuck_reg_no"=>$row['truck_reg_no'],"source"=>$row['source_city'],"destination"=>$row['destination_city'],"truck_type"=>$row['truck_type'],"date_available"=>$row['date_available'],'tracking'=>$row['tracking_available'],"comment"=>'',"price"=>$row['price'],"select"=>$row['interested']);
			}
            /*$json['data'][]=array("tid"=>"1","tuck_reg_no"=>"ap36k7172","source"=>"hyderabad","destination"=>"bombay","truck_type"=>"10 feet truck","date_available"=>"2016-01-11",'tracking'=>1,"comment"=>"","price"=>"1800","select"=>1);
			$json['data'][]=array("tid"=>"2","tuck_reg_no"=>"ap24k7172","source"=>"hyderabad","destination"=>"bombay","truck_type"=>"10 feet truck","date_available"=>"2016-01-11",'tracking'=>1,"comment"=>"","price"=>"1800","select"=>0);*/
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actiongetTrucksAvailable() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/getTrucksAvailable
          method=post
        */

        $json = array("status" => 0);
        //if (Yii::app()->request->isPostRequest) {
        $offset = (int)$_REQUEST['offset'];
		$uid = (int)$_REQUEST['uid'];
        if (1) {
            $json['status'] = 1;
			$total=Yii::app()->db->createCommand("select count(*) as total from eg_notify_transporter_available_trucks where date_available>=date(Now())")->queryScalar(); //>=
			$json['count'] = (int)$total;
			$rows=Yii::app()->db->createCommand("select ntat.*,(select tt.title from eg_truck_type tt where tt.id_truck_type=ntat.id_truck_type) as truck_type,(select count(*) from eg_notify_transporter_available_trucks_customers ntatc where ntatc.id_customer='".$uid."' and ntatc.id_notify_transporter_available_trucks=ntat.id_notify_transporter_available_trucks) as interested from eg_notify_transporter_available_trucks ntat where ntat.date_available>=date(Now()) order by ntat.date_available asc limit " . $this->limit . " offset " . $offset)->queryAll();

			foreach($rows as $row){
				$json['data'][]=array("tid"=>$row['id_notify_transporter_available_trucks'],"tuck_reg_no"=>"","source"=>$row['source_city'],"destination"=>$row['destination_city'],"truck_type"=>$row['truck_type'],"date_available"=>$row['date_available'],'tracking'=>"","comment"=>'',"price"=>$row['price'],"no_of_trucks"=>$row['no_of_trucks'],"select"=>$row['interested']);
			}
            /*$json['data'][]=array("tid"=>"1","tuck_reg_no"=>"ap36k7172","source"=>"hyderabad","destination"=>"bombay","truck_type"=>"10 feet truck","date_available"=>"2016-01-11",'tracking'=>1,"comment"=>"","price"=>"1800","select"=>1);
			$json['data'][]=array("tid"=>"2","tuck_reg_no"=>"ap24k7172","source"=>"hyderabad","destination"=>"bombay","truck_type"=>"10 feet truck","date_available"=>"2016-01-11",'tracking'=>1,"comment"=>"","price"=>"1800","select"=>0);*/
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actionBookTrucksAvailable() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/BookTrucksAvailable
          method=post
          params=tid,uid,price
          status=0,1
          1:success
          0:faile
         new param uid
        */

        $json = array("status" => 1);
        $tid = $_REQUEST['tid'];
        $uid = $_REQUEST['uid'];
        $price = $_REQUEST['price'];
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            Notifytransporteravailabletruckscustomers::model()->deleteAll('id_customer="'.$uid.'" and id_notify_transporter_available_trucks="'.$tid.'"');
            $obj=new Notifytransporteravailabletruckscustomers();
            $obj->id_customer=$uid;
            $obj->expected_price=$price;
            $obj->id_notify_transporter_available_trucks=$tid;
            $obj->save(false);
            $json['status']=1;    
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }


	public function actionTrackOrderedTruck() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/TrackOrderedTruck
          method=post
          params=uid,oid
         modified
         uid
        */

        $json = array("status" => 0);
        $uid = $_REQUEST['uid'];
		$oid = $_REQUEST['oid'];

		
        //$truckregno = strtolower(str_replace(' ','',$_REQUEST['truckregno']));
        if (1) {
            $json['status'] = 1;
            $trRow=Yii::app()->db->createCommand("select *,UNIX_TIMESTAMP(date_ordered) as unx_date_ordered,UNIX_TIMESTAMP( date_ordered + INTERVAL 3 
DAY ) as unx_date_ordered_3  from eg_order where id_order='".$oid."'")->queryRow();
            if($trRow){
				//echo "select latitude,longitude,FROM_UNIXTIME(creationTime,'%M-%D %h:%i:%s') from EventData where (creationTime>='".$trRow['unx_date_ordered']."' and creationTime<='".$trRow['unx_date_ordered_3']."' ) and  (speedKPH!=0 and  lower(deviceID)='".strtolower(str_replace(' ','',$trRow['truck_reg_no']))."' order by creationTime desc)";
                $rows=Yii::app()->db_gts->createCommand("select latitude,longitude,FROM_UNIXTIME(creationTime,'%M-%D %h:%i:%s') as dateCreated from EventData where (creationTime>='".$trRow['unx_date_ordered']."' and creationTime<='".$trRow['unx_date_ordered_3']."' ) and  (speedKPH!=0 and  lower(deviceID)='".strtolower(str_replace(' ','',$trRow['truck_reg_no']))."') order by creationTime desc")->queryAll();
				$json['data']=$rows;
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	    public function actionforgotpassword1() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/forgotpassword
          method=post
          params=userName
          status=0,1
          1:success
          0:faile
         new param uid
        */

        $json = array("statusCode" => 1);
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            $json['success']="Msg has been sent";    
            //$json['error']="Msg has been sent";    
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actionforgotpassword() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/forgotpassword
          method=post
          params=userName
          status=0,1
          1:success
          0:faile
         new param uid
        */
        $userName=$_REQUEST['userName'];    
        $json = array("statusCode" => 0);
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            //$json['error']="Msg has been sent";
            $accRow=Yii::app()->db_gts->createCommand("select password,contactPhone from Account where accountID='".$userName."'")->queryRow();
            if($accRow['password']!=""){
                $password = Admin::hashPassword($accRow['password']);
                Customer::model()->updateAll(array('password'=>$password),'gps_account_id="'.$accRow['accountID'].'"');
                Library::sendSingleSms(array('to'=>$accRow['contactPhone'],'message'=>"Password:".$accRow['password']));
                $json['statusCode']=1;    
                $json['success']="Msg has been sent";    
            }else{
                $custRow=Yii::app()->db->createCommand("select id_customer,mobile,gps_account_id from eg_customer where mobile='".$userName."'")->queryRow();
                if((int)$custRow['id_customer']){
                    $tempPassword=Library::randomPassword();
                    $password = Admin::hashPassword($tempPassword);
                    Customer::model()->updateAll(array('password'=>$password),'id_customer="'.$custRow['id_customer'].'"');
                    if($custRow['gps_account_id']!=""){
                    Yii::app()->db_gts->createCommand("update Account set password='".$tempPassword."' where accountID='".$custRow['gps_account_id']."'")->query();
                    }
                    Library::sendSingleSms(array('to'=>$userName,'message'=>"Password:".$tempPassword));
                    $json['statusCode']=1;    
                    $json['success']="Msg has been sent";
                }else{
                    $json['statusCode']=1;    
                    $json['success']="Invalid Username";
                }
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actiontrackallvehicles() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/trackallvehicles
          method=post
          params=account_id
        */
            $account_id=$_REQUEST['account_id'];    
            $json=array();
            $json['statusCode']=0;

            //if(($account_id!="") && (Yii::app()->request->isPostRequest)){
            if($account_id!=""){
                if($account_id!="egattached"){
                        $query = "select deviceID as truck_no,deviceID,ifnull(lastValidLatitude,0) as latitude,ifnull(lastValidLongitude,0) as longitude,ceil(lastValidSpeedKPH) as speed, (ifnull(lastUpdateTime,0)+19800) as time_in_secs,FROM_UNIXTIME(ifnull(lastUpdateTime,0), '%Y %D %M %h:%i') as date_time,lastOdometerKM as odometer from Device where accountID = '".$account_id."' ";

                }else{
                        $query = "select deviceID as truck_no,deviceID,lastValidLatitude as latitude,lastValidLongitude as longitude,lastValidSpeedKPH as speed, (ifnull(lastUpdateTime,0)+19800) as time_in_secs,FROM_UNIXTIME(ifnull(lastUpdateTime,0), '%Y %D %M %h:%i') as date_time,lastOdometerKM as odometer from Device where deviceID in (select deviceID from AttachedDevice)";
                }
                $rows=Yii::app()->db_gts->createCommand($query)->queryAll();
                if(is_array($rows) && sizeof($rows)){
                        $json['statusCode']=1;
						
					$acctDetails=Yii::app()->db_gts->createCommand("select contactPhone,contactName from Account where accountID='".$account_id."'")->queryRow();
					$i=0;
					foreach($rows as $row){
						$success[$i]=$row;
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

	public function actiontracktruck() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/tracktruck
          method=post
		  truckregno
		  from
		  to
          params=account_id
        */
            //$from=strtotime($_REQUEST['from']);
			//$to=strtotime($_REQUEST['to']);
			$from=$_REQUEST['from'];
			$to=$_REQUEST['to'];
			$truckregno=$_REQUEST['truckregno'];
            $json=array();
            $json['statusCode']=0;
			//echo $from-$to." ".$from." ".$to;exit;
            //if(($account_id!="") && (Yii::app()->request->isPostRequest)){
            if($truckregno!="" && $from!="" && $to!=""){
                $query="SELECT ifnull(latitude,0) as latitude, ifnull(longitude,0) as longitude, ceil(speedKPH) as speed, (ifnull(timestamp,0)+19800) as time_in_secs, FROM_UNIXTIME(ifnull(timestamp,0), '%Y %D %M %k:%i') as date_time,heading FROM EventData WHERE speedKPH!=0 and deviceID like '".$truckregno."' AND timestamp <= unix_timestamp('".$to."') AND timestamp >=unix_timestamp('".$from."')  ORDER BY timestamp";
				

				$query2="SELECT unix_timestamp('".$to."') as too,unix_timestamp('".$from."') as fromm,max(odometerKM) as max_odokm,min(odometerKM) as min_odokm,ceil(sum(distanceKM)) as distancetravelled,ceil((sum(speedKPH)/count(*))) as avgspeed FROM EventData WHERE speedKPH!=0 and deviceID like '".$truckregno."' AND timestamp <= unix_timestamp('".$to."') AND timestamp >=unix_timestamp('".$from."')";
				//echo $query." ".$query2;exit;
				//echo '<pre>';print_r($rows);

				//$ttime=0;
				/*foreach($rows as $k=>$v){
					//echo $k.$v;	
					//print_r($v);
					$ttime+=(int)$rows[$k+1]['time_in_secs']-$v['time_in_secs'];
					echo $ttime."+=".(int)$rows[$k+1]['time_in_secs']."-".$v['time_in_secs']."<br/>";
				}*/	
				/*for($i=0;$i<sizeof($rows)-1;$i++){
					if($rows[$i]['speed']>5){
					$ttime+=$rows[$i+1]['time_in_secs']-$rows[$i]['time_in_secs'];
					echo $ttime."+=".(int)$rows[$i+1]['time_in_secs']."-".$rows[$i]['time_in_secs']."<br/>";
					}
					
				}*/
				//exit($ttime);	
				$rows=Yii::app()->db_gts->createCommand($query)->queryAll();	
				$info=Yii::app()->db_gts->createCommand($query2)->queryRow();
                $timeTravelled=floor($info[distancetravelled]/$info[avgspeed]);
				$idleTime=floor((($info[too]-$info[fromm])-($timeTravelled*3600))/3600);
				//echo "<pre>";print_r(array("tt"=>$timeTravelled,"too"=>$info[too],"fromm"=>$info[fromm]));
				//exit;
				if($info[avgspeed]<30){
					$running_status='Running Slow';
				}else if($info[avgspeed]>30 && $info[avgspeed]<55){
					$running_status='Running on Time';
				}else if($info[avgspeed]>55){
					$running_status='Running Fast';
				}
				if(is_array($rows) && sizeof($rows)){
                    $json['statusCode']=1;
					$json['success']['odokm']=$info[max_odokm]-$info[min_odokm];
					$json['success']['from']=$from;
					$json['success']['to']=$to;
					$json['success']['diff']=$to-$from;
					$json['success']['idleTime']=$idleTime." Hours";
					$json['success']['distanceTravelled']=$info[distancetravelled];
					$json['success']['timeTravelled']=$timeTravelled." Hours";
					$json['success']['averageSpeed']=$info[avgspeed];
					$json['success']['runningStatus']=$running_status;
                    $json['success']['points']=$rows;
                }else{
                    $json['error'][]=array("msg"=>"No Data Found");
                }
            }else{
                $json['error'][]=array("msg"=>"No Data Found");
            }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
}
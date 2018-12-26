<?php

class GPSapiV3Controller extends Controller {

    public $layout = "//layouts/guest";
    public $limit = 10;
    public $egAccounts = array('egattached', 'egmumbai');

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

    /* public function actionGetAlerts() { //need to upload image

      //http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/GetAlerts
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
      } */

    public function actionGetAlerts() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/GetAlerts
          method=post
          param=accountid
          new param uid
         */
        $json = array("status" => 0);
        $accountid = $_REQUEST['accountid'];
        $uid = $_REQUEST['uid'];
        $type = $_REQUEST['type'];
        //if (Yii::app()->request->isPostRequest) {
        $rows = array();
        if (1) {
            if ($type == 'T') {
                $select = "ga.*, DATE_FORMAT( ga.date_required,    '%D %b %h:%i %p' )  as date_required";
            } else if ($type == 'C') {
                $select = "ga.id_gps_alerts,ga.source,ga.destination,ga.id_truck_type_title,ga.price, DATE_FORMAT( ga.date_required,    '%D %b %h:%i %p' )  as date_required";
            } else {
                $select = "ga.*, DATE_FORMAT( ga.date_required,    '%D %b %h:%i %p' )  as date_required";
            }

            /* $rows[0] = Yii::app()->db->CreateCommand("select ".$select." from eg_gps_alerts ga where ga.sendtoall=1 and date(ga.date_created)+ INTERVAL 30 DAY >=NOW() order by ga.date_created desc")->queryAll();
              $rows[1] = Yii::app()->db->CreateCommand("select ".$select." from eg_gps_alerts ga,eg_gps_alerts_users gau where ga.id_gps_alerts=gau.id_gps_alerts and (gau.id_customer='".$uid."') and ga.sendtoall=0 and date(ga.date_created)+ INTERVAL 30 DAY >=NOW() order by ga.date_created desc")->queryAll(); */

            $rows[0] = Yii::app()->db->CreateCommand("select " . $select . " from eg_gps_alerts ga where ga.sendtoall=1 and date(ga.date_required)+ INTERVAL 3 DAY >=NOW() order by ga.date_required desc")->queryAll();
            $rows[1] = Yii::app()->db->CreateCommand("select " . $select . " from eg_gps_alerts ga,eg_gps_alerts_users gau where ga.id_gps_alerts=gau.id_gps_alerts and (gau.id_customer='" . $uid . "') and ga.sendtoall=0 and date(ga.date_required)+ INTERVAL 3 DAY >=NOW() order by ga.date_required desc")->queryAll();

            $rows = array_merge($rows[0], $rows[1]);
            //echo '<pre>';print_r($rows);
            $json['status'] = 1;
            foreach ($rows as $row) {
                $json['data'][] = array("id_goods_type" => $row[id_goods_type] == "" ? "" : $row[id_goods_type],
                    "id_truck_type" => $row[id_truck_type] == "" ? "" : $row[id_truck_type],
                    "id_gps_alerts" => $row[id_gps_alerts] == "" ? "" : $row[id_gps_alerts],
                    "notified" => $row[notified] == "" ? "" : $row[notified],
                    "sendtoall" => $row[sendtoall] == "" ? "" : $row[sendtoall],
                    "date_required" => $row[date_required] == "" ? "" : $row[date_required],
                    "date_created" => $row[date_required] == "" ? "" : $row[date_required],
                    "accountid" => $row[accountid] == "" ? "" : $row[accountid],
                    "message" => $row[message] == "" ? "" : $row[message],
                    "price" => $row[price] == "" ? "" : $row[price],
                    "id_goods_type_title" => $row[id_goods_type_title] == "" ? "" : $row[id_goods_type_title],
                    "source" => "Hyderabad",//$row[source] == "" ? "" : str_replace(", India", "", $row[source]),
                    "destination" => "Vijayawada",//$row[destination] == "" ? "" : str_replace(", India", "", $row[destination]),
                    "source_state"=>"Telangana",
                    "destination_state"=>"Andhra Pradesh",
                    
                    "id_truck_type_title" => $row[id_truck_type_title] == "" ? "" : $row[id_truck_type_title],
                    "id_goods_type_title" => $row[id_goods_type_title] == "" ? "" : $row[id_goods_type_title]
                );
            }
            //$json['count'] = $count;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionSetTruckInfo() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/SetTruckInfo
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
        if (1) {
            Gpstrucklocation::model()->deleteAll('id_customer="' . $uid . '" and date(date_created)="' . date('Y-m-d') . '"');
            $json['status'] = 1;
            $model = new Gpstrucklocation;
            $model->accountid = $accountid;
            $model->id_customer = $uid;
            $model->address = $address;
            $model->truck_reg_no = $truck_reg_no;
            $model->mobile = $mobile;
            $model->date_available = $date_available;
            $model->save(false);

            $custInfo = $this->getCustomer($uid);
            $message = $custInfo->fullname . "(" . $custInfo->idprefix . " " . $mobile . ") updated Truck Avail Location " . $truck_reg_no . " in  " . $address . " date: " . $date_available;
            $this->sendMail(array("subject" => $message, "message" => $message));
            $json['msg'] = 'Thankyou,Loyality points will be added once confirmed!!';
        }
        echo CJSON::encode($json);
        if ($json['status']) {
            $this->sendPushNotification(array("message" => $message));
        }
        Yii::app()->end();
    }

    public function actionGetTrucks() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/GetTrucks
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
            $trRows = Yii::app()->db->createCommand("select distinct replace(trim(tr.truck_reg_no),' ','') as truck_reg_no,tr.id_truck,tr.vehicle_insurance_expiry_date,tr.fitness_certificate_expiry_date,tr.rc_no,tr.national_permit_available,tr.national_permit_expiry_date,tr.insurance_amount from eg_truck tr where tr.id_customer='" . $uid . "' and tracking_available=0")->queryAll();
            $json['data'] = $trRows;

            /* if(count($trRows)){
              foreach($trRows as $trRow){
              $json['data'][]=$trRow['truck_reg_no'];
              }
              } */
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionLogin() {

        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/Login
          method=post
          params=userName,password
         */

        $json = array("statusCode" => 0);
        $userName = $_REQUEST['userName'];
        $password = $_REQUEST['password'];
        $deviceid = $_REQUEST['deviceid'];
        //if (Yii::app()->request->isPostRequest && $userName!="" && $password!="") {
        if (1) {

            $user = Customer::model()->find('islead=0 and status=1 and (type="L" or type="T" or type="TR" or type="C")  and (mobile="' . $userName . '" or gps_account_id="' . $userName . '")');
            //truck owners only
            if ($user === null) {
                $json['statusCode'] = 0;
                $json['error'][] = array('msg' => "Invalid Username/Password.", 'value' => array("userName" => $userName));
            } else if (!$user->validatePassword($password)) {
                $json['statusCode'] = 0;
                $json['error'][] = array('msg' => "Invalid Username/Password..", 'value' => array("userName" => $userName));
            } else {
                $json['statusCode'] = 1;
                $json['success']['uid'] = $user->id_customer;
                $json['success']['type'] = $user->type;
                $json['success']['accountID'] = $user->gps_account_id == "" ? $user->mobile : $user->gps_account_id;
                $json['success']['contactName'] = $user->fullname;
                $json['success']['contactPhone'] = $user->mobile;
                $json['success']['contactEmail'] = $user->email;
                if ($user->type == 'T') {
                    $json['success']['gps'] = $user->gps_account_id == "" ? 0 : 1; //gps account exists then 1
                    $json['success']['truck'] = 0;
                    $json['success']['loads'] = 1;
                    $json['success']['postload'] = 1; //0;
                    $json['success']['loadstatus'] = 1; //0;
                    $json['success']['orders'] = 0;
                    $json['success']['truckavailable'] = 1; //0;
                    $json['success']['buyselltrucks'] = 1; //0;
                } else if ($user->type == 'TR') {
                    $json['success']['gps'] = $user->gps_account_id == "" ? 0 : 1; //gps account exists then 1
                    $json['success']['truck'] = 0; //0;
                    $json['success']['loads'] = 1; //0;
                    $json['success']['postload'] = 1;
                    $json['success']['loadstatus'] = 1;
                    $json['success']['orders'] = 0;
                    $json['success']['truckavailable'] = 1;
                    $json['success']['buyselltrucks'] = 1; //0;
                } else if ($user->type == 'C') {
                    $json['success']['gps'] = $user->gps_account_id == "" ? 0 : 1; //gps account exists then 1
                    $json['success']['truck'] = 0;
                    $json['success']['loads'] = 1;
                    $json['success']['postload'] = 1; //0;
                    $json['success']['loadstatus'] = 1; //0;
                    $json['success']['orders'] = 0; //0;
                    $json['success']['truckavailable'] = 1; //0;
                    $json['success']['buyselltrucks'] = 1; //0;
                }

                /* = array('accountID' => $user->mobile, 'contactName' => $user->fullname, 'contactPhone' => $user->alt_mobile_1, 'contactEmail' => $user->email, 'gps' => 0, 'truck' => 1); */
                $this->addLoginDevice(array('username' => $userName, 'deviceid' => $deviceid));
            }
        }
        $checkAccount = $user->gps_account_id == "" ? $userName : $user->gps_account_id;
        $json['egAccount'] = in_array($checkAccount, $this->egAccounts) ? 1 : 0;

        echo CJSON::encode($json);
        Yii::app()->end();
    }

    function addLoginDevice($data) {
        if ($data['deviceid'] != "") {
            //Yii::app()->db->createCommand("delete from  eg_gps_login_device where username like '".$data['username']."'")->query();
            Yii::app()->db->createCommand("insert into eg_gps_login_device(username,device_id) values('" . $data['username'] . "','" . $data['deviceid'] . "')")->query();
        }
    }

    public function actionSetTruck() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/SetTruck
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
        $id_truck_type = (int) $_REQUEST['id_truck_type'];
        $uid = (int) $_REQUEST['uid'];

        $id_truck = (int) $_REQUEST['id_truck'];
        $fitness_expiry_date = $_REQUEST['fitness_expiry_date'];
        $insurance_expiry_date = $_REQUEST['insurance_expiry_date'];
        $rc_no = $_REQUEST['rc_no'];
        $np_available = $_REQUEST['np_available'];
        $np_expiry_date = $_REQUEST['np_expiry_date'];
        $insurance_amount = $_REQUEST['insurance_amount'];
        if ($truck_reg_no && $truck_reg_no != "" && !$id_truck) {
            $truckObj = new Truck;
            $truckObj->truck_reg_no = $truck_reg_no;
            $truckObj->id_truck_type = $id_truck_type;
            $truckObj->id_customer = $uid;
            $truckObj->date_created = new CDbExpression('NOW()');
            $truckObj->vehicle_insurance_expiry_date = $insurance_expiry_date;
            $truckObj->fitness_certificate_expiry_date = $fitness_expiry_date;
            $truckObj->rc_no = $rc_no;
            $truckObj->national_permit_available = $np_available;
            $truckObj->national_permit_expiry_date = $np_expiry_date;
            $truckObj->insurance_amount = $insurance_amount;
            $truckObj->save(false);
            $json['status'] = 1;

            $custInfo = $this->getCustomer($uid);
            $message = $custInfo->fullname . "(" . $custInfo->idprefix . " " . $custInfo->mobile . ") added Truck " . $truck_reg_no;
            $this->sendMail(array("subject" => $message, "message" => $message));
        } else {
            $json['status'] = 1;
            Truck::model()->updateAll(array('vehicle_insurance_expiry_date' => $insurance_expiry_date, 'fitness_certificate_expiry_date' => $fitness_expiry_date, 'rc_no' => $rc_no, 'national_permit_available' => $np_available, 'national_permit_expiry_date' => $np_expiry_date, 'insurance_amount' => $insurance_amount), "id_truck='" . $id_truck . "'");
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionSetCustomer() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/SetCustomer
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
        if ($mobile != "" && $fullname != "" && $address != "") {
            $count = Yii::app()->db->createCommand("select count(*) as count from eg_customer where mobile='" . $mobile . "' and type!='G'")->queryScalar();
            if (!$count) {
                //$password=Library::randomPassword();//temporarly kept status 0 from 1 for quick registrations and login
                $password = strtolower(str_replace(" ", "", $fullname));
                $password = strlen($password) < 5 ? $password . "@123" : $password;
                $this->addCustomer(array('status' => 1, 'type' => $type, 'fullname' => $fullname, 'mobile' => $mobile, 'password' => $password, 'address' => $address));
                //exit($mobile." mobile and password ".$password);
                Library::sendSingleSms(array('to' => $mobile, 'message' => "username: " . $mobile . " , password:" . $password));
                $json['status'] = 1;
                if ($type == 'T') {
                    $mType = 'TO';
                }
                $message = $fullname . "(" . $mType . " " . $mobile . ") New Customer Sign Up";
                $this->sendMail(array("subject" => $message, "message" => $message));
            } else {
                $json['status'] = '-1';
            }
        }
        echo CJSON::encode($json);
        if (!$count) {
            $this->sendPushNotification(array("message" => $message));
        }
        Yii::app()->end();
    }

    public function addCustomer($input) {
        $CustObj = new Customer;
        $CustObj->password = Admin::hashPassword($input['password']);
        $CustObj->islead = 0; //temporarly kept 0 from 1 
        $CustObj->type = $input['type'] == '' ? 'T' : $input['type']; //'T';
        $CustObj->fullname = $input['fullname'];
        $CustObj->mobile = $input['mobile'];
        $CustObj->address = $input['address'];
        $CustObj->status = $input['status'];
        $CustObj->date_created = new CDbExpression('NOW()');
        $CustObj->save(false);

        $CustleadObj = new Customerlead;
        $CustleadObj->id_customer = $CustObj->id_customer;
        $CustleadObj->lead_status = 'Initiated';
        $CustleadObj->lead_source = 'Truck App';
        $CustleadObj->save(false);
        return $CustObj->id_customer;
    }

    public function actionSetLoad() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/SetLoad
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
            $obj = new Gpsalertsinterested();
            $obj->id_gps_alert = $id;
            $obj->expected_price = $price;
            $obj->account_id = $accountid;
            $obj->id_customer = $uid;
            $obj->save(false);
            $json['status'] = 1;

            $custInfo = $this->getCustomer($uid);
            $gpsalertInfo = $this->getGpsalert($id);
            $message = $custInfo->fullname . "(" . $custInfo->idprefix . " " . $custInfo->mobile . ") Interested In Load " . $gpsalertInfo->source . " " . $gpsalertInfo->destination . " for " . $gpsalertInfo->date_required . " price :" . $gpsalertInfo->price;
            $this->sendMail(array("subject" => $message, "message" => $message));
        }
        echo CJSON::encode($json);

        if ($json['status']) {
            $this->sendPushNotification(array("message" => $message));
        }

        Yii::app()->end();
    }

    public function actiontestsms() {
        //Library::sendSingleSms(array('to'=>'9848750094','message'=>"password:"));
    }

    public function actionGetGoodsType() {
        //http://egcrm.cloudapp.net/operations/index.php/GPSapi/GetGoodsType
        $rows = Yii::app()->db->createCommand('select id_goods_type,title from {{goods_type}} where status=1')->queryAll();
        $return = array();
        foreach ($rows as $row) {
            $return[] = array('Key' => $row['id_goods_type'], 'Value' => $row['title']);
        }
        echo CJSON::encode($return);
        Yii::app()->end();
    }

    public function actionGetOrders() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/GetOrders
          method=post
          params=uid,type,offset
          modified
          uid
         */

        $json = array("status" => 0);
        //$accountid = $_REQUEST['accountid'];
        //if (Yii::app()->request->isPostRequest) {
        $uid = $_REQUEST['uid'];
        $type = $_REQUEST['type'];
        $offset = (int) $_REQUEST['offset'];
        if (1) {
            $json['status'] = 1;
            $trRows = Yii::app()->db->createCommand("select amount,truck_reg_no,truck_type,goods_type,order_status_name,id_order,source_city,destination_city,DATE_FORMAT( date_ordered,  '%d-%m-%Y %h:%i %p' )  as date_ordered from eg_order where (id_customer='" . $uid . "' or id_customer_ordered='" . $uid . "') order by date_ordered desc limit " . $this->limit . " offset " . $offset)->queryAll();
            $total = Yii::app()->db->createCommand("select count(*) from eg_order where (id_customer='" . $uid . "' or id_customer_ordered='" . $uid . "')")->queryScalar();
            $json['count'] = $total;
            if (count($trRows)) {
                //$json['data']=$trRows;
                foreach ($trRows as $trRow) {
                    //$date_future=strtotime($trRow['date_ordered'])+86400;
                    //echo "select  count(*) from Device where (creationTime<'".strtotime($trRow['date_ordered'])."') and deviceID='".strtolower(str_replace(' ','',$trRow['truck_reg_no']))."'";
                    $track = Yii::app()->db_gts->createCommand("select  count(*) from Device where deviceID='" . strtolower(str_replace(' ', '', $trRow['truck_reg_no'])) . "'")->queryScalar();
                    $json['data'][] = array("current_location" => "", "order_amount" => $trRow['amount'], "pending_amount" => "", "tracking" => $track, "truck_reg_no" => $trRow['truck_reg_no'], "truck_type" => $trRow['truck_type'], "goods_type" => $trRow['goods_type'], "order_status_name" => $trRow['order_status_name'], "id_order" => $trRow['id_order'], "source_city" => $trRow['source_city'], "destination_city" => $trRow['destination_city'], "date_ordered" => $trRow['date_ordered']);
                }
            } else {
                $json['data'] = array();
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionSetPostLoad() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/SetPostLoad
          method=post
          params=uid,source_address,destination_address,pickup_point,expected_price,comment,tracking,id_truck_type,id_goods_type,date_required
         */

        $json = array("status" => 0);
        $data = $_REQUEST;
        //if (Yii::app()->request->isPostRequest) {
        $uid = $_REQUEST['uid'];
        if (1) {

            $model = new Loadtruckrequest();
            $src = Library::getGPDetails($data['source_address']);
            $dest = Library::getGPDetails($data['destination_address']);
            $row = Admin::model()->getLeastAssigmentIdSearch();
            $custObj = Customer::model()->find('id_customer="' . $uid . '"');
            $model->id_customer = $uid;
            $model->id_admin_assigned = $row['id_admin'];
            $model->title = $custObj->idprefix . "," . $custObj->fullname . "," . Library::getCustomerType($custObj->type) . "," . $custObj->mobile . "," . $custObj->email;
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
            $model->no_of_trucks = $data['no_of_trucks'];
            $model->id_goods_type = $data['id_goods_type'];
            $model->date_required = trim($data['date_required']);
            $model->date_created = new CDbExpression('NOW()');
            if ($model->validate()) {
                $model->save(false);
                $json['status'] = 1;

                $message = $custObj->fullname . "(" . $custObj->idprefix . " " . $custObj->mobile . ") Posted Load " . $model->source_city . " " . $model->destination_city . " for " . $model->date_required . " price :" . $model->expected_price;
                $this->sendMail(array("subject" => $message, "message" => $message));
            } else {
                $json['errors'] = $model->getErrors();
            }
            //echo '<pre>';print_r($model->getErrors());echo '</pre>';
        }
        echo CJSON::encode($json);
        if ($json['status']) {
            $this->sendPushNotification(array("message" => $message));
        }
        Yii::app()->end();
    }

    public function actionGetPostLoads() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/GetPostLoads
          method=post
          params=uid,offset
         */

        $json = array("status" => 0);
        //$accountid = $_REQUEST['accountid'];
        //if (Yii::app()->request->isPostRequest) {
        //$uid = 644;//$_REQUEST['uid'];
        $uid = $_REQUEST['uid'];
        $offset = (int) $_REQUEST['offset'];
        if (1) {
            $json['status'] = 1;
            $total = Yii::app()->db->createCommand("select count(*) as total from eg_load_truck_request ltr where ltr.id_customer='" . $uid . "' and  ltr.isactive=1 order by  ltr.id_load_truck_request")->queryScalar();
            $json['count'] = (int) $total;
            $trRows = Yii::app()->db->createCommand("select (select count(*) as count from eg_load_truck_request_quotes ltrq where ltrq.id_load_truck_request=ltr.id_load_truck_request) as quotecount,status,tracking,comment,ifnull((select gt.title from eg_goods_type gt where gt.id_goods_type=ltr.id_goods_type),'') as goods_type,ifnull((select tt.title from eg_truck_type tt where tt.id_truck_type=ltr.id_truck_type),'') as truck_type,ltr.id_load_truck_request,ltr.expected_price,DATE_FORMAT( ltr.date_required,  '%d-%m-%Y' )  as date_required,ltr.id_goods_type,ltr.id_truck_type,ltr.pickup_point,ltr.comment,ltr.source_city,ltr.destination_city,ltr.date_created,ltr.no_of_trucks,ltr.loading_charge,ltr.unloading_charge from eg_load_truck_request ltr where ltr.id_customer='" . $uid . "' and  ltr.isactive=1 order by  ltr.id_load_truck_request desc limit " . $this->limit . " offset " . $offset)->queryAll();
            if (count($trRows)) {
                foreach ($trRows as $trRow) {
                    //$trRow['quotes'][]=array('id'=>123,'cid'=>'C123','quote'=>1200,'message'=>'ready to move','booking_request'=>0);
                    $json['data'][] = $trRow;
                }
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionGetQuotes() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/GetQuotes
          method=post
          params=lid
         */

        $json = array("status" => 0);
        //$accountid = $_REQUEST['accountid'];
        //if (Yii::app()->request->isPostRequest) {
        $lid = (int) $_REQUEST['lid'];
        if (1) {
            $json['status'] = 1;
            //echo "select c.idprefix,ltrq.* from eg_customer c,eg_load_truck_request_quotes ltrq where c.id_customer=ltrq.id_customer and ltrq.id_load_truck_request='".$lid."'";
            //$rows=Loadtruckrequestquotes::model()->findAll('id_load_truck_request="'.$lid.'"');
            $rows = Yii::app()->db->createCommand("select c.idprefix,ltrq.* from eg_customer c,eg_load_truck_request_quotes ltrq where c.id_customer=ltrq.id_customer and ltrq.id_load_truck_request='" . $lid . "'")->queryAll();
            foreach ($rows as $row) {
                $json['data'][] = array('qid' => $row['id_load_truck_request_quotes'], 'cid' => $row['idprefix'], 'quote' => $row['quote'], 'message' => $row['message'], 'booking_request' => $row['booking_request']);
            }
            //$json['data'][]=array('qid'=>123,'cid'=>'C123','quote'=>1200,'message'=>'ready to move','booking_request'=>0);
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionselectLoadQuote() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/selectLoadQuote
          method=post
          params=qid
         */

        $json = array("status" => 0);
        //if (Yii::app()->request->isPostRequest) {
        $qid = $_REQUEST['qid'];
        $offset = (int) $_REQUEST['offset'];
        if (1) {
            $json['status'] = 1;
            Loadtruckrequestquotes::model()->updateAll(array('booking_request' => 1), 'id_load_truck_request_quotes="' . $qid . '"');

            $quoteInfo = $this->getLoadQuoteDetails($qid);
            $message = $quoteInfo[title] . " : Booked Load/Quote " . $quoteInfo[source_city] . " " . $quoteInfo[destination_city] . " for " . $quoteInfo[date_required] . " quote :" . $quoteInfo[quote];
            $this->sendMail(array("subject" => $message, "message" => $message));
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionselectTrucksAvailable() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/selectTrucksAvailable
          method=post
          uid,tid
         */

        $json = array("status" => 0);
        //if (Yii::app()->request->isPostRequest) {
        $uid = (int) $_REQUEST['uid'];
        $tid = (int) $_REQUEST['tid'];
        if (1) {
            Truckloadrequestinterested::model()->deleteAll('id_customer="' . $uid . '" and id_truck_load_request="' . $tid . '"');
            $json['status'] = 1;
            $model = new Truckloadrequestinterested;
            $model->id_customer = $uid;
            $model->id_truck_load_request = $tid;
            $model->save(false);
            $json['tid'] = $tid;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionDeleteTruck() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/DeleteTruck
          method=post
          tid
         */

        $json = array("status" => 0);
        $tid = (int) $_REQUEST['tid'];
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            $json['status'] = 1;
            Truck::model()->deleteAll('id_truck="' . $tid . '"');
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionCancelPostedLoad() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/CancelPostedLoad
          method=post
          lid
         */

        $json = array("status" => 0);
        $lid = (int) $_REQUEST['lid'];
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            $json['status'] = 1;
            Loadtruckrequest::model()->updateAll(array('isactive' => 0), 'id_load_truck_request="' . $lid . '"');
            $loadInfo = $this->getLoadDetails($lid);
            $message = $loadInfo[title] . ": Canceled Load From " . $loadInfo[source_city] . " " . $loadInfo[destination_city];
            $this->sendMail(array("subject" => $message, "message" => $message));
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actiongetTrucksAvailable() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/getTrucksAvailable
          method=post
         */

        $json = array("status" => 0);
        //if (Yii::app()->request->isPostRequest) {
        $offset = (int) $_REQUEST['offset'];
        $uid = (int) $_REQUEST['uid'];
        if (1) {
            $json['status'] = 1;
            $total = Yii::app()->db->createCommand("select count(*) as total from eg_notify_transporter_available_trucks where date_available>=date(Now())")->queryScalar(); //>=
            $json['count'] = (int) $total;
            $rows = Yii::app()->db->createCommand("select ntat.*,DATE_FORMAT( ntat.date_available,'%d-%m-%Y' )  as date_available,(select tt.title from eg_truck_type tt where tt.id_truck_type=ntat.id_truck_type) as truck_type,(select count(*) from eg_notify_transporter_available_trucks_customers ntatc where ntatc.id_customer='" . $uid . "' and ntatc.id_notify_transporter_available_trucks=ntat.id_notify_transporter_available_trucks) as interested from eg_notify_transporter_available_trucks ntat where ntat.date_available>=date(Now()) order by ntat.date_available asc limit " . $this->limit . " offset " . $offset)->queryAll();

            foreach ($rows as $row) {
                $json['data'][] = array("tid" => $row['id_notify_transporter_available_trucks'], "tuck_reg_no" => "", "source" => $row['source_city'], "destination" => $row['destination_city'], "truck_type" => $row['truck_type'], "date_available" => $row['date_available'], 'tracking' => "", "comment" => '', "price" => $row['price'], "no_of_trucks" => $row['no_of_trucks'], "select" => $row['interested']);
            }
            /* $json['data'][]=array("tid"=>"1","tuck_reg_no"=>"ap36k7172","source"=>"hyderabad","destination"=>"bombay","truck_type"=>"10 feet truck","date_available"=>"2016-01-11",'tracking'=>1,"comment"=>"","price"=>"1800","select"=>1);
              $json['data'][]=array("tid"=>"2","tuck_reg_no"=>"ap24k7172","source"=>"hyderabad","destination"=>"bombay","truck_type"=>"10 feet truck","date_available"=>"2016-01-11",'tracking'=>1,"comment"=>"","price"=>"1800","select"=>0); */
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionBookTrucksAvailable() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/BookTrucksAvailable
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
            Notifytransporteravailabletruckscustomers::model()->deleteAll('id_customer="' . $uid . '" and id_notify_transporter_available_trucks="' . $tid . '"');
            $obj = new Notifytransporteravailabletruckscustomers();
            $obj->id_customer = $uid;
            $obj->expected_price = $price;
            $obj->id_notify_transporter_available_trucks = $tid;
            $obj->save(false);
            $json['status'] = 1;

            $custInfo = $this->getCustomer($uid);
            $trucknotifyInfo = $this->getNotifytransporteravailabletrucks($tid);
            $message = $custInfo->fullname . "(" . $custInfo->idprefix . " " . $custInfo->mobile . ") Transporter Booked Truck " . $trucknotifyInfo[source_city] . " to " . $trucknotifyInfo[destination_city] . " for " . $trucknotifyInfo[date_available] . " " . $trucknotifyInfo[truck_type] . " price " . $price;
            $this->sendMail(array("subject" => $message, "message" => $message));
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionTrackOrderedTruck() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/TrackOrderedTruck
          method=post
          params=uid,oid
          modified
          uid
         */

        $json = array("statusCode" => 0);
        $uid = $_REQUEST['uid'];
        $oid = $_REQUEST['oid'];


        //$truckregno = strtolower(str_replace(' ','',$_REQUEST['truckregno']));
        if (1) {
            $json['statusCode'] = 1;
            $trRow = Yii::app()->db->createCommand("select *,UNIX_TIMESTAMP(date_ordered) as unx_date_ordered,UNIX_TIMESTAMP( date_ordered + INTERVAL 3 
DAY ) as unx_date_ordered_3  from eg_order where id_order='" . $oid . "'")->queryRow();
            if ($trRow) {
                //echo "select latitude,longitude,FROM_UNIXTIME(creationTime,'%M-%D %h:%i:%s') from EventData where (creationTime>='".$trRow['unx_date_ordered']."' and creationTime<='".$trRow['unx_date_ordered_3']."' ) and  (speedKPH!=0 and  lower(deviceID)='".strtolower(str_replace(' ','',$trRow['truck_reg_no']))."' order by creationTime desc)";
                $rows = Yii::app()->db_gts->createCommand("select (ifnull(timestamp,0)+19800) as time_in_secs,heading,concat(ceil(speedKPH),' ','Km/Hr') as speed, FROM_UNIXTIME(ifnull(timestamp,0), '%Y %D %M %k:%i') as date_time,latitude,longitude,FROM_UNIXTIME(creationTime,'%M-%D %h:%i:%s') as dateCreated from EventData where (creationTime>='" . $trRow['unx_date_ordered'] . "' and creationTime<='" . $trRow['unx_date_ordered_3'] . "' ) and  (speedKPH!=0 and  lower(deviceID)='" . strtolower(str_replace(' ', '', $trRow['truck_reg_no'])) . "') order by creationTime desc")->queryAll();
                $from = $trRow['unx_date_ordered'];
                $to = $trRow['unx_date_ordered_3'];
                $truckregno = strtolower(str_replace(' ', '', $trRow['truck_reg_no']));
                $query2 = "SELECT max(odometerKM) as max_odokm,min(odometerKM) as min_odokm,ceil(sum(distanceKM)) as distancetravelled,ceil((sum(speedKPH)/count(*))) as avgspeed FROM EventData WHERE speedKPH!=0 and lower(deviceID) like '" . $truckregno . "' AND creationTime <= '" . $to . "' AND creationTime >='" . $from . "'";

                //$json['data']=$rows;
                $info = Yii::app()->db_gts->createCommand($query2)->queryRow();
                $timeTravelled = floor($info[distancetravelled] / $info[avgspeed]);
                $idleTime = floor((($info[too] - $info[fromm]) - ($timeTravelled * 3600)) / 3600);
                //echo "<pre>";print_r(array("tt"=>$timeTravelled,"too"=>$info[too],"fromm"=>$info[fromm]));
                //exit;
                if ($info[avgspeed] < 30) {
                    $running_status = 'Running Slow';
                } else if ($info[avgspeed] > 30 && $info[avgspeed] < 55) {
                    $running_status = 'Running on Time';
                } else if ($info[avgspeed] > 55) {
                    $running_status = 'Running Fast';
                }
                $json['statusCode'] = 1;
                $json['success']['odokm'] = ceil($info[max_odokm] - $info[min_odokm]) . " Km";
                $json['success']['from'] = $from;
                $json['success']['to'] = $to;
                $json['success']['diff'] = $to - $from;
                $json['success']['idleTime'] = $idleTime . " Hours";
                $json['success']['distanceTravelled'] = $info[distancetravelled] . " Km";
                $json['success']['timeTravelled'] = $timeTravelled . " Hours";
                $json['success']['averageSpeed'] = $info[avgspeed] . " Km/Hr";
                $json['success']['runningStatus'] = $running_status;
                $json['success']['points'] = $rows;
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionforgotpassword() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/forgotpassword
          method=post
          params=userName
          status=0,1
          1:success
          0:faile
          new param uid
         */
        $userName = trim($_REQUEST['userName']);
        $json = array("statusCode" => 0);
        $smsNumber = "";
        $smsMsg = "";
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            //$json['error']="Msg has been sent";
            $accRow = Yii::app()->db_gts->createCommand("select password,contactPhone from Account where accountID='" . $userName . "'")->queryRow();
            if ($accRow['password'] != "") {
                /* $password = Admin::hashPassword($accRow['password']);
                  Customer::model()->updateAll(array('password'=>$password),'gps_account_id="'.$accRow['accountID'].'"'); */

                $tempPassword = Library::randomPassword();
                $password = Admin::hashPassword($tempPassword);
                //Customer::model()->updateAll(array('password'=>$password),'id_customer="'.$custRow['id_customer'].'"');
                Customer::model()->updateAll(array('password' => $password), 'gps_account_id="' . $userName . '"');
                Yii::app()->db_gts->createCommand("update Account set password='" . $tempPassword . "' where accountID='" . $userName . "'")->query();

                $smsNumber = $accRow['contactPhone'];
                $smsMsg = "Password:" . $tempPassword; //$accRow['password'];
                //Library::sendSingleSms(array('to'=>$accRow['contactPhone'],'message'=>"Password:".$accRow['password']));
                $json['statusCode'] = 1;
                $json['success'] = "Msg has been sent";
            } else {
                $custRow = Yii::app()->db->createCommand("select id_customer,mobile,gps_account_id from eg_customer where mobile='" . $userName . "'")->queryRow();
                if ((int) $custRow['id_customer']) {
                    $tempPassword = Library::randomPassword();
                    $password = Admin::hashPassword($tempPassword);
                    Customer::model()->updateAll(array('password' => $password), 'id_customer="' . $custRow['id_customer'] . '"');
                    if ($custRow['gps_account_id'] != "") {
                        Yii::app()->db_gts->createCommand("update Account set password='" . $tempPassword . "' where accountID='" . $custRow['gps_account_id'] . "'")->query();
                    }
                    $smsNumber = $userName;
                    $smsMsg = "Password:" . $tempPassword;
                    //Library::sendSingleSms(array('to'=>$userName,'message'=>"Password:".$tempPassword));
                    $json['statusCode'] = 1;
                    $json['success'] = "Msg has been sent";
                } else {
                    $json['statusCode'] = 1;
                    $json['success'] = "Invalid Username";
                }
            }
        }
        echo CJSON::encode($json);
        if ($smsNumber != "" && $smsMsg != "") {
            Library::sendSingleSms(array('to' => $smsNumber, 'message' => $smsMsg));
        }
        Yii::app()->end();
    }
    
    public function actiontrackallvehicles() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV2/trackallvehicles
          method=post
          params=account_id
        */
            //echo '<pre>';print_r($this->egAccounts);echo '</pre>';exit;
            $account_id=$_REQUEST['account_id'];    
            $json=array();
            $json['statusCode']=0;
			
            $q=$_REQUEST['q'];
            $gid=(int)$_REQUEST['gid'];
            $group=$_REQUEST['group'];
            $json['group']=$group;
            $srchQry="";
            if($q!=""){
                    $srchQry=" deviceID like '%".$q."%' and ";
            }

            if($gid!=0){
                    $srchQry.=" groupID = '".$gid."' and ";
            }

            //if(($account_id!="") && (Yii::app()->request->isPostRequest)){
            if($account_id!=""){
                
				//if($account_id=="egmumbai"){
					if($account_id=="easydemo"){
					//mumbai surroundinb vehicles around 150km radius
                    $pointLat='18.864474444444443';
                    $pointLng= '73.20685444444445';
                    $radius=150;
					$duetime=strtotime('now')-7200;
                    $query="SELECT lastValidHeading,lastUpdateTime,(select concat(contactName,',',contactPhone) from Account where Account.accountID=Device.accountID) as contact, deviceID as truck_no,deviceID,ifnull(lastValidLatitude,0) as latitude,ifnull(lastValidLongitude,0) as longitude,ceil(lastValidSpeedKPH*1.852) as speedValue, (ifnull(lastGPSTimestamp,0)) as time_in_secs,FROM_UNIXTIME(ifnull(lastGPSTimestamp,0), '%d-%m-%y %H:%i') as date_time,ceil(lastOdometerKM) as odometer, ( 3959 * acos( cos( radians(".$pointLat.") ) * cos( radians(lastValidLatitude) ) * cos( radians(lastValidLongitude) - radians(".$pointLng.") ) + sin( radians(".$pointLat.") ) * sin( radians(lastValidLatitude) ) ) ) AS distance,vehicleType,
vehicleModel,lookingForLoad,lookingForLoadDate FROM Device where isActive=1 and lastGPSTimestamp>".$duetime." HAVING  distance < ".$radius." ORDER BY distance limit 20";    

                }else if($account_id=="egattached"){
						$query = "select lastValidHeading,lastUpdateTime,(select concat(contactName,',',contactPhone) from Account where Account.accountID=Device.accountID) as contact,deviceID as truck_no,deviceID,lastValidLatitude as latitude,lastValidLongitude as longitude,ceil(lastValidSpeedKPH*1.852) as speedValue, (ifnull(lastGPSTimestamp,0)) as time_in_secs,FROM_UNIXTIME(ifnull(lastGPSTimestamp,0), '%d-%m-%y %H:%i') as date_time,ceil(ifnull(lastOdometerKM,0)) as odometer,vehicleType,
vehicleModel,lookingForLoad,lookingForLoadDate from Device where deviceID in (select deviceID from AttachedDevice) order by lastValidSpeedKPH desc";                        

                }else{
						$query = "select lastValidHeading,lastUpdateTime,deviceID as truck_no,deviceID,ifnull(lastValidLatitude,0) as latitude,ifnull(lastValidLongitude,0) as longitude,ceil(lastValidSpeedKPH*1.852) as speedValue, (ifnull(lastGPSTimestamp,0)) as time_in_secs,FROM_UNIXTIME(ifnull(lastGPSTimestamp,0), '%d-%m-%y %H:%i') as date_time,ceil(ifnull(lastOdometerKM,0)) as odometer,vehicleType,
vehicleModel,lookingForLoad,lookingForLoadDate from Device where  isActive=1 and ".$srchQry." accountID = '".$account_id."'  order by lastValidSpeedKPH desc";
                }

                $rows=Yii::app()->db_gts->createCommand($query)->queryAll();
                if(is_array($rows) && sizeof($rows)){
                        $json['statusCode']=1;
						
					$acctDetails=Yii::app()->db_gts->createCommand("select stopAlertTime,contactPhone,contactName from Account where accountID='".$account_id."'")->queryRow();
					$i=0;
					$date=date('Y-m-d');
					$currentTime=strtotime('now');
                                        $damageTime=$currentTime-10800; //3hours
                                        $currentDate=strtotime(date('Y-m-d'));
					foreach($rows as $row){
						$success[$i]=$row;
						
						$loc=Library::getGPBYLATLNGDetailsGoogle($row["latitude"].",".$row["longitude"]);
						$addrArr['address']=substr(str_replace(', ',',',$loc['address']),0,-13);
                                                //exit($currentDate.' and accountID="'.$account_id.'" and deviceID="'.$row['truck_no'].'" limit 1');
                                                $eDataOdo=GpsEventData::model()->find(array('select'=>'odometerKM','condition'=>'timestamp >= '.$currentDate.' and accountID="'.$account_id.'" and deviceID="'.$row['truck_no'].'"'));
                                                
                                                $success[$i]['todayOdo']=(round($row['odometer']-$eDataOdo->odometerKM,2))." Km";
                                                $success[$i]['odometer']=$row['odometer']." Km";
                                                $speed=$row['speedValue']>6?$row['speedValue']:0;
                                                if($row['time_in_secs']<$damageTime){
                                                        $success[$i]['momentStatus']='Damage';
                                                        $success[$i]['momentMsg']='GPS Connection Lost.Contact Support!';
                                                }else if($row['lastUpdateTime']-$row['time_in_secs']>3600){
                                                        $success[$i]['momentStatus']='Damage';
                                                        $success[$i]['momentMsg']='GPS Signal Unavailable!';
                                                }else if($speed){
                                                    $success[$i]['momentStatus']='Running';
                                                    $success[$i]['momentMsg']='';
                                                }else if(!$speed){
                                                    $stopAlertTime=$currentTime-($acctDetails['stopAlertTime']*60);
                                                    $eData=GpsEventData::model()->find(array('select'=>'sum(distanceKM) as accountID','condition'=>'timestamp>='.$stopAlertTime.' and timestamp<='.$currentTime.' and accountID="'.$account_id.'" and deviceID="'.$row['truck_no'].'"'));
                                                    $stopKM=(float)$eData->accountID;
                                                    if($stopKM<=1){
                                                            $success[$i]['momentStatus']="Long Stop";
                                                            $success[$i]['momentMsg']='';
                                                    }else{
                                                            $success[$i]['momentStatus']="Stop";
                                                            $success[$i]['momentMsg']='';
                                                    }
                                                }
						$success[$i]['speedValue']=$speed;
						$success[$i]['speed']=$speed.' Km/Hr';
						$success[$i]['address']=$addrArr['address'];
						//$success[$i]['vehicleType']='CR';
						
						$success[$i]['time_in_secs']=$row['time_in_secs'];//<$damageTime?"1448980829":$row['time_in_secs'];
						$success[$i]['truck_owner']=$acctDetails['contactName'];
						$success[$i]['truck_owner_phone']=$acctDetails['contactPhone'];
						$success[$i]['lookingForLoad']=(($row['lookingForLoadDate']==$date) && $row['lookingForLoad'])?1:0;
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
        
    public function actiontrackallvehicles_march_8_2017() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV2/trackallvehicles
          method=post
          params=account_id
         */
        //echo '<pre>';print_r($this->egAccounts);echo '</pre>';exit;
        $account_id = $_REQUEST['account_id'];
        $json = array();
        $json['statusCode'] = 0;

        $q = $_REQUEST['q'];
        $gid = (int) $_REQUEST['gid'];
        $group = $_REQUEST['group'];
        $json['group'] = $group;
        $srchQry = "";
        if ($q != "") {
            $srchQry = " deviceID like '%" . $q . "%' and ";
        }

        if ($gid != 0) {
            $srchQry.=" groupID = '" . $gid . "' and ";
        }

        //if(($account_id!="") && (Yii::app()->request->isPostRequest)){
        if ($account_id != "") {

            //if($account_id=="egmumbai"){
            if ($account_id == "easydemo") {
                //mumbai surroundinb vehicles around 150km radius
                $pointLat = '18.864474444444443';
                $pointLng = '73.20685444444445';
                $radius = 150;
                $duetime = strtotime('now') - 7200;
                $query = "SELECT (select concat(contactName,',',contactPhone) from Account where Account.accountID=Device.accountID) as contact, deviceID as truck_no,deviceID,ifnull(lastValidLatitude,0) as latitude,ifnull(lastValidLongitude,0) as longitude,ceil(lastValidSpeedKPH*1.852) as speedValue, (ifnull(lastGPSTimestamp,0)) as time_in_secs,FROM_UNIXTIME(ifnull(lastGPSTimestamp,0), '%d-%m-%y %H:%i') as date_time,concat(ceil(lastOdometerKM),' ','Km') as odometer, ( 3959 * acos( cos( radians(" . $pointLat . ") ) * cos( radians(lastValidLatitude) ) * cos( radians(lastValidLongitude) - radians(" . $pointLng . ") ) + sin( radians(" . $pointLat . ") ) * sin( radians(lastValidLatitude) ) ) ) AS distance,vehicleType,
vehicleModel,lookingForLoad,lookingForLoadDate,fitnessExpire,insuranceExpire,rcNo,NPAvailable,NPExpire,insuranceAmount FROM Device where isActive=1 and lastGPSTimestamp>" . $duetime . " HAVING  distance < " . $radius . " ORDER BY distance limit 20";
            } else if ($account_id == "egattached") {
                $query = "select (select concat(contactName,',',contactPhone) from Account where Account.accountID=Device.accountID) as contact,deviceID as truck_no,deviceID,lastValidLatitude as latitude,lastValidLongitude as longitude,ceil(lastValidSpeedKPH*1.852) as speedValue, (ifnull(lastGPSTimestamp,0)) as time_in_secs,FROM_UNIXTIME(ifnull(lastGPSTimestamp,0), '%d-%m-%y %H:%i') as date_time,concat(ceil(ifnull(lastOdometerKM,0)),' ','Km') as odometer,vehicleType,
vehicleModel,lookingForLoad,lookingForLoadDate,fitnessExpire,insuranceExpire,rcNo,NPAvailable,NPExpire,insuranceAmount from Device where deviceID in (select deviceID from AttachedDevice) order by lastValidSpeedKPH desc";
            } else {

                $query = "select deviceID as truck_no,deviceID,ifnull(lastValidLatitude,0) as latitude,ifnull(lastValidLongitude,0) as longitude,ceil(lastValidSpeedKPH*1.852) as speedValue, (ifnull(lastGPSTimestamp,0)) as time_in_secs,FROM_UNIXTIME(ifnull(lastGPSTimestamp,0), '%d-%m-%y %H:%i') as date_time,concat(ceil(ifnull(lastOdometerKM,0)),' ','Km') as odometer,vehicleType,
vehicleModel,lookingForLoad,lookingForLoadDate,fitnessExpire,insuranceExpire,rcNo,NPAvailable,NPExpire,insuranceAmount from Device where  isActive=1 and " . $srchQry . " accountID = '" . $account_id . "'  order by lastValidSpeedKPH desc";
            }

            $rows = Yii::app()->db_gts->createCommand($query)->queryAll();
            if (is_array($rows) && sizeof($rows)) {
                $json['statusCode'] = 1;

                $acctDetails = Yii::app()->db_gts->createCommand("select contactPhone,contactName from Account where accountID='" . $account_id . "'")->queryRow();
                $i = 0;
                $date = date('Y-m-d');
                $damageTime = strtotime('now') - 10800; //3hours
                $currentTime = strtotime('now');
                foreach ($rows as $row) {
                    $success[$i] = $row;
                    //$addr=Library::getGPBYLATLNGDetails($row[latitude].",".$row[longitude]);
                    //google maps
                    //$addr=Library::getGPBYLATLNGDetailsForMobile($row[latitude].",".$row[longitude]);
                    //$success[$i]['address']=substr(str_replace(', ',',',$addr[address]),0,-13);
                    //eg gps server
                    /* $addrArr=Library::getGPBYLATLNGDetailsCloud($row[latitude].",".$row[longitude]);
                      if((substr(trim($addrArr['address']),0,5)=='NH16,') && $account_id=='skmastanvali'){ //using googlemaps api for skmastanvali
                      $loc=Library::getGPBYLATLNGDetailsGoogle($row["latitude"].",".$row["longitude"]);
                      $addrArr['address']=$loc['address']!=""?$loc['address']:$addrArr['address'];
                      } */

                    /* if($account_id=='sampath'){ //using googlemaps api for skmastanvali
                      $loc=Library::getGPBYLATLNGDetailsGoogle($row["latitude"].",".$row["longitude"]);
                      $addrArr['address']=$loc['address']!=""?$loc['address']:$addrArr['address'];
                      } */

                    $loc = Library::getGPBYLATLNGDetailsGoogle($row["latitude"] . "," . $row["longitude"]);
                    $addrArr['address'] = substr(str_replace(', ', ',', $loc['address']), 0, -13); //$loc['address']!=""?$loc['address']:$addrArr['address'];
                    if ($addrArr['address'] == "") {
                        $addrArr = Library::getGPBYLATLNGDetailsCloud($row[latitude] . "," . $row[longitude]);
                    }

                    //$success[$i]['speedValue']=trim(str_replace("Km/Hr","",$row['speed']));
                    $speed = $row['speedValue'] > 6 ? $row['speedValue'] : "0";
                    $success[$i]['speedValue'] = $speed;
                    $success[$i]['speed'] = $speed . ' Km/Hr';
                    $success[$i]['address'] = $addrArr['address'];

                    //$success[$i]['damage']=$row['time_in_secs']<$damageTime?"1":"0";

                    if ($row['time_in_secs'] < $damageTime) {
                        $success[$i]['damage'] = "1";
                    } else if ($account_id == 'ayyappacargo') {
                        //if(!$row['speedValue']){
                        if (1) {
                            $eData = GpsEventData::model()->find(array('select' => 'sum(distanceKM) as accountID', 'condition' => 'timestamp>=' . $damageTime . ' and timestamp<=' . $currentTime . ' and accountID="' . $account_id . '" and deviceID="' . $row['truck_no'] . '"'));
                            $stopKM = (float) $eData->accountID;
                            //echo $row['truck_no']." ".$stopKM."<br/>";
                            //echo 'timestamp>='.$damageTime.' and timestamp<='.$currentTime.' and accountID="'.$account_id.'" and deviceID="'.$row['truck_no'];exit;
                            if ($stopKM <= 1) {
                                $success[$i]['damage'] = "1";
                            } else {
                                $success[$i]['damage'] = "0";
                            }
                        } else {
                            $success[$i]['damage'] = "0";
                        }
                    } else {
                        $success[$i]['damage'] = "0";
                    }

                    $success[$i]['time_in_secs'] = $row['time_in_secs']; //<$damageTime?"1448980829":$row['time_in_secs'];
                    $success[$i]['truck_owner'] = $acctDetails['contactName'];
                    $success[$i]['truck_owner_phone'] = $acctDetails['contactPhone'];
                    $success[$i]['lookingForLoad'] = (($row['lookingForLoadDate'] == $date) && $row['lookingForLoad']) ? 1 : 0;
                    $i++;
                }

                $json['success'] = $success;
            } else {
                $json['error'][] = array("msg" => "No Device Found");
            }
        } else {
            $json['error'][] = array("msg" => "No Device Found");
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actiontracktruck() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/tracktruck
          method=post
          truckregno
          from
          to
          params=account_id
         */
        //$from=strtotime($_REQUEST['from']);
        //$to=strtotime($_REQUEST['to']);
        $from = $_REQUEST['from'];
        $to = $_REQUEST['to'];
        $truckregno = $_REQUEST['truckregno'];
        $accountid = $_REQUEST['accountid'];
        $json = array();
        $json['statusCode'] = 0;
        //echo $from-$to." ".$from." ".$to;exit;
        //if(($account_id!="") && (Yii::app()->request->isPostRequest)){
        if ($truckregno != "" && $from != "" && $to != "") {
            $str = $accountid != "" ? " accountID like '" . $accountid . "' and " : "";
            $query = "SELECT ifnull(latitude,0) as latitude, ifnull(longitude,0) as longitude, speedKPH, (ifnull(timestamp,0)+19800) as time_in_secs, FROM_UNIXTIME(ifnull(timestamp,0), '%Y %D %M %k:%i') as date_time,heading,odometerKM,distanceKM FROM EventData WHERE " . $str . "  deviceID like '" . $truckregno . "' AND timestamp <= unix_timestamp('" . $to . "') AND timestamp >=unix_timestamp('" . $from . "')  ORDER BY timestamp";

            //echo $query;//exit;		
            $too = strtotime($to);
            $fromm = strtotime($to);

            $rows = Yii::app()->db_gts->createCommand($query)->queryAll();
            //echo '<pre>';print_r($rows);exit;
            if ($accountid != "") {
                $rowAccount = Yii::app()->db_gts->createCommand("select stopDurationLimit,overSpeedLimit from Account where accountID='" . $accountid . "'")->queryRow();
                $stopDurationLimit = $rowAccount[stopDurationLimit] == 0 ? '300' : $rowAccount[stopDurationLimit] * 60;
                $overSpeedLimit = $rowAccount[overSpeedLimit] == 0 ? '70' : $rowAccount[overSpeedLimit];
            } else {
                $stopDurationLimit = 600;
                $overSpeedLimit = 70;
            }
            $max_odokm = 0;
            $distancetravelled = 0;
            $avgspeed = 0;
            $speedsum = 0;
            $points = array();
            $stops = array();
            $loop = 0;
            $stopDuration = 900; //seconds,15 mins
            $lastStopTime = 0;
            $stopTime = 0;
            $stopLoop = 0;
            $topSpeed = 0;
            $i = 0;
            $startStopNew = 1;
            $startingStopTime = 0;
            foreach ($rows as $row) {
                if ($row['speedKPH']) {
                    $max_odokm = $row['odometerKM'] > $max_odokm ? $row['odometerKM'] : $max_odokm;
                    $topSpeed = $row['speedKPH'] > $topSpeed ? $row['speedKPH'] : $topSpeed;
                    $distancetravelled+=$row['distanceKM'];
                    $speedsum+=$row['speedKPH'];
                    $points[$loop] = $row;
                    $points[$loop]['speed'] = ceil($row['speedKPH']) . " Km/Hr";
                    $points[$loop]['speedValue'] = ceil($row['speedKPH']);
                    $loop++;
                } else {

                    if ($startStopNew) {
                        $startingStopTime = $row['time_in_secs'];
                        $startStopNew = 0;
                    }

                    if ($rows[$i + 1]['speedKPH']) {
                        $stopTime = $rows[$i + 1]['time_in_secs'] - $startingStopTime;
                        if ($stopTime > $stopDurationLimit) {
                            $stops[$stopLoop] = array("latitude" => $row['latitude'], "longitude" => $row['longitude'], "date_time" => $row['date_time'], "time_in_secs" => $startingStopTime, "stopTime" => $stopTime, "distance" => 0);
                            $stopLoop++;
                        }
                        $startStopNew = 1;
                    }
                }
                $i++;
            }
//lastPoint
            if (is_array($rows[$i - 1])) {
                $points[$loop] = $rows[$i - 1];
                $points[$loop]['speed'] = ceil($rows[$i - 1]['speedKPH']) . " Km/Hr";
                $points[$loop]['speedValue'] = ceil($rows[$i - 1]['speedKPH']);
            }

            $avgspeed = $speedsum / $loop;
            $distancetravelled = ceil($distancetravelled);
            $avgspeed = ceil($avgspeed);
            $timeTravelled = floor($distancetravelled / $avgspeed);
            $idleTime = floor((($too - $fromm) - ($timeTravelled * 3600)) / 3600);

            if ($avgspeed < 30) {
                $running_status = 'Running Slow';
            } else if ($avgspeed > 30 && $avgspeed < 55) {
                $running_status = 'Running on Time';
            } else if ($avgspeed > 55) {
                $running_status = 'Running Fast';
            }
            if (is_array($points) && sizeof($points)) {
                $json['statusCode'] = 1;
                //$json['success']['odokm']=ceil($info[max_odokm]-$info[min_odokm])." Km";
                $json['success']['speedLimit'] = $overSpeedLimit;
                $json['success']['stops'] = $stops;
                $json['success']['topSpeed'] = ceil($topSpeed);
                $json['success']['odokm'] = ceil($max_odokm) . " Km";
                $json['success']['from'] = $from;
                $json['success']['to'] = $to;
                $json['success']['diff'] = $too - $fromm;
                $json['success']['idleTime'] = $idleTime . " Hours";
                $json['success']['distanceTravelled'] = $distancetravelled . " Km";
                $json['success']['timeTravelled'] = $timeTravelled . " Hours";
                $json['success']['averageSpeed'] = $avgspeed . " Km/Hr";
                $json['success']['runningStatus'] = $running_status;
                $json['success']['points'] = $points;
            } else {
                $json['error'][] = array("msg" => "Vehicle not moved between $from to $to");
            }
        } else {
            $json['error'][] = array("msg" => "Vehicle not moved between $from to $to");
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return round($miles * 1.7); //1.609344
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public function sendMail($data) {
        $headers .= 'From: <info@easygaadi.com>' . "\r\n";
        mail("info@easygaadi.com", $data['subject'], $data['message'], $headers);
        $this->addSendPushNotification(array("message" => $data['message']));
    }

    public function addSendPushNotification($data) {
        $devices = Admin::model()->getFieldTeamDeviceID();
        /* $model=new Notificationstruckapp;
          $model->message=$data['message'];
          $model->save(false); */
        $result = Library::sendPushNotificationDCApp(array('devices' => $devices, 'message' => $data['message']));
        //echo $result;
    }

    public function getCustomer($id) {
        return Customer::model()->find('id_customer="' . $id . '"');
    }

    public function getGpsalert($id) {
        return Gpsalerts::model()->find('id_gps_alerts="' . $id . '"');
    }

    public function getLoadQuoteDetails($qid) {
        return Yii::app()->db->createCommand("select ltr.*,ltrq.quote from eg_load_truck_request ltr,eg_load_truck_request_quotes ltrq where ltr.id_load_truck_request=ltrq.id_load_truck_request and ltrq.id_load_truck_request_quotes='" . $qid . "'")->queryRow();
    }

    public function getLoadDetails($qid) {
        return Yii::app()->db->createCommand("select ltr.* from eg_load_truck_request ltr where  ltr.id_load_truck_request='" . $qid . "'")->queryRow();
    }

    public function getNotifytransporteravailabletrucks($tid) {
        //return Notifytransporteravailabletrucks::model()->find('id_notify_transporter_available_trucks="'.$tid.'"');
        return Yii::app()->db->createCommand("select ntat.*,(select t.title from eg_truck_type t where t.id_truck_type=ntat.id_truck_type) as truck_type from eg_notify_transporter_available_trucks ntat where ntat.id_notify_transporter_available_trucks='" . $tid . "'")->queryRow();
    }

    public function actionlogoutDevice() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/logoutDevice
          method=post
          params=userName
          status=0,1
          1:success
          0:faile
          new param uid
         */
        $uid = $_REQUEST['uid'];
        $json = array("status" => 0);
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            $row = Yii::app()->db->createCommand("select mobile,gps_account_id from eg_customer where id_customer='" . $uid . "'")->queryRow();
            //Yii::app()->db->createCommand("delete from  eg_gps_login_device where username in ('".$row['mobile']."','".$row['gps_account_id']."')")->query();
            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionisUserActive() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/isUserActive
          method=post
          params=username,uid
          status=0,1
          1:success
          0:faile
         */
        $json = array('status' => 1);
        $id_admin = (int) $_REQUEST['id_admin'];
        $deviceid = $_REQUEST['deviceid'];
        //if (Yii::app()->request->isPostRequest) {
        $row = Yii::app()->db->createCommand("select mobile,gps_account_id from eg_customer where id_customer='" . $id_admin . "'")->queryRow();
        if ($row['mobile'] != "" && $deviceid != "") {
            //Yii::app()->db->createCommand("delete from  eg_gps_login_device where username in  ('".$row['mobile']."','".$row['gps_account_id']."')")->query();
            Yii::app()->db->createCommand("insert into eg_gps_login_device(username,device_id) values('" . $row['gps_account_id'] . "','" . $deviceid . "')")->query();
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionUploadSellTruck() {
        /* $picfields=array('truck_front_pic','truck_back_pic','tyres_front_left_pic','tyres_front_right_pic','tyres_back_left_pic','tyres_back_right_pic','other_pic_1','other_pic_1');
          $dataFile=array();
          $picfield="truck_front_pic"; */
        //http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/UploadSellTruck?id_sell_truck=1&field=truck_front_pic
        $field = $_REQUEST['field'];
        $id = (int) $_REQUEST['id_sell_truck'];
        $json['status'] = 0;
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            $data = $_FILES['image'];
            $data['input']['prefix'] = $field . '_' . $id . '_';
            $data['input']['path'] = Library::getTruckSellUploadPath();
            $upload = Library::fileUpload($data);
            //$dataFile[$picfield]=$upload['file'];
            //echo Library::getTruckSellUploadPath().'<pre>';print_r($data);print_r($upload);print_r($_FILES);exit;		
            if ($upload['status']) {
                Selltruck::model()->updateAll(array($field => $upload['file']), 'id_sell_truck="' . $id . '"');
                $json['status'] = 1;
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionaddSellTruck() {

        //error_reporting(E_ALL);
        //ini_set('display_errors',1);
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/addSellTruck?uid=1&truck_reg_no=ap36k7271&contact_name=hello&contact_mobile=998877
          method=post
          params=username,uid
          status=0,1
          1:success
          0:faile
         */
        $json = array('status' => 1);
        $data = array();
        $data['id_customer'] = (int) $_REQUEST['uid'];
        $data['gps_account_id'] = $_REQUEST['account_id'];
        $data['truck_reg_no'] = $_REQUEST['truck_reg_no'];
        $data['id_truck_type'] = $_REQUEST['id_truck_type'];
        $data['truck_type_title'] = $_REQUEST['truck_type_title'];
        $data['contact_name'] = $_REQUEST['contact_name'];
        $data['contact_mobile'] = $_REQUEST['contact_mobile'];
        $data['truck_reg_state'] = $_REQUEST['truck_reg_state'];
        $data['insurance_exp_date'] = $_REQUEST['insurance_exp_date'];
        $data['fitness_exp_date'] = $_REQUEST['fitness_exp_date'];
        $data['year_of_mfg'] = $_REQUEST['year_of_mfg'];
        $data['odometer'] = $_REQUEST['odometer'];
        $data['any_accidents'] = $_REQUEST['any_accidents'];
        $data['in_finance'] = $_REQUEST['in_finance'];
        $data['expected_price'] = $_REQUEST['expected_price'];
        $data['isactive'] = 1;
        $data['status'] = 1;
        $data['date_created'] = new CDbExpression('NOW()');
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            $Selltruck = new Selltruck;
            $Selltruck->attributes = $data;
            if ($Selltruck->validate()) {
                $Selltruck->save(false);
                /* $picfields=array('truck_front_pic','truck_back_pic','tyres_front_left_pic','tyres_front_right_pic','tyres_back_left_pic','tyres_back_right_pic','other_pic_1','other_pic_1');
                  $dataFile=array();
                  foreach($picfields as $picfield){
                  $data=$_FILES[$picfield];
                  $data['input']['prefix'] = $picfield.'_' . $Selltruck->id_sell_truck.'_';
                  $data['input']['path'] = Library::getTruckSellUploadPath();
                  $upload = Library::fileUpload($data);
                  $dataFile[$picfield]=$upload['file'];
                  }
                  Selltruck::model()->updateAll(array('truck_front_pic'=>$dataFile['truck_front_pic'],'truck_back_pic'=>$dataFile['truck_back_pic'],'tyres_front_left_pic'=>$dataFile['tyres_front_left_pic'],'tyres_front_right_pic'=>$dataFile['tyres_front_right_pic'],'tyres_back_left_pic'=>$dataFile['tyres_back_left_pic'],'tyres_back_right_pic'=>$dataFile['tyres_back_right_pic'],'other_pic_1'=>$dataFile['other_pic_1'],'other_pic_1'=>$dataFile['other_pic_1']),'id_sell_truck="'.$Selltruck->id_sell_truck.'"'); */
                $message = "New Sell Truck by " . $data['contact_name'] . "," . $data['contact_mobile'] . " ,Reg No " . $data['truck_reg_no'] . "(" . $data['truck_type_title'] . "),price " . $data['expected_price'];
                $this->sendMail(array("subject" => $message, "message" => $message));
                //$this->sendPushNotification(array("message"=>$message));

                $json['status'] = 1;
                $json['id_sell_truck'] = $Selltruck->id_sell_truck;
                $json['error'] = "";
            } else {
                $json['status'] = 0;
                $json['error'] = "some of the fields missing";
            }
        }
        echo CJSON::encode($json);
        if ($json['status']) {
            $this->sendPushNotification(array("message" => $message));
        }
        Yii::app()->end();
    }

    public function actionGetSellTrucks() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/GetSellTrucks?uid=1&accountid=santosh&offset=0
          method=post
          params=uid,accountid,offset
         */

        $json = array("status" => 0);

        //if (Yii::app()->request->isPostRequest) {
        $uid = (int) $_REQUEST['uid'];
        $accountid = $_REQUEST['accountid'];
        $cond = $uid ? "sti.id_customer='" . $uid . "'" : "sti.gps_account_id='" . $accountid . "'";
        //$offset = 50;//(int)$_REQUEST['offset'];
        $offset = (int) $_REQUEST['offset'];
        if (1) {
            $json['status'] = 1;
            $trRows = Yii::app()->db->createCommand("select st.*,ifnull((select sti.status from eg_sell_truck_interested sti where sti.id_sell_truck=st.id_sell_truck and (" . $cond . ") order by sti.id_sell_truck_interested limit 1),-1) as customer_status from eg_sell_truck st where st.isactive=1 order by st.date_created desc limit " . $this->limit . " offset " . $offset)->queryAll();
            //echo "select st.*,ifnull((select sti.status from eg_sell_truck_interested sti where sti.id_sell_truck=st.id_sell_truck and (".$cond.") order by sti.id_sell_truck_interested limit 1),-1) as customer_status from eg_sell_truck st where st.isactive=1 order by st.date_created desc limit ".$this->limit." offset ".$offset;
            $total = Yii::app()->db->createCommand("select count(*) from eg_sell_truck where isactive=1")->queryScalar();
            $json['count'] = $total;
            if (count($trRows)) {
                $i = 0;
                $picfields = array('truck_front_pic', 'truck_back_pic', 'tyres_front_left_pic', 'tyres_front_right_pic', 'tyres_back_left_pic', 'tyres_back_right_pic', 'other_pic_1', 'other_pic_2');
                foreach ($trRows as $trRow) {
                    $json['data'][$i] = $trRow;
                    foreach ($picfields as $k => $picfield) {
                        if ($trRow[$picfield] != "") {
                            $json['data'][$i][$picfield] = Library::getTruckSellUploadLink() . $trRow[$picfield];
                        }
                    }
                    $i++;
                }
            } else {
                $json['data'] = array();
            }
        }

        header('Content-Type: application/json');
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionGetSellTruckPics() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/GetSellTruckPics?id_sell_truck=1
          method=post
          params=id_sell_truck
         */

        $json = array("status" => 0);

        //if (Yii::app()->request->isPostRequest) {
        $json['status'] = 0;
        $id_sell_truck = (int) $_REQUEST['id_sell_truck'];
        if (1) {
            $json['status'] = 1;
            $trRow = Yii::app()->db->createCommand("select truck_front_pic,truck_back_pic,tyres_front_left_pic,tyres_front_right_pic,tyres_back_left_pic,tyres_back_right_pic,other_pic_1,other_pic_2 from eg_sell_truck where id_sell_truck='" . $id_sell_truck . "'")->queryRow();

            if (count($trRow)) {
                $picfields = array('truck_front_pic', 'truck_back_pic', 'tyres_front_left_pic', 'tyres_front_right_pic', 'tyres_back_left_pic', 'tyres_back_right_pic', 'other_pic_1', 'other_pic_2');
                foreach ($picfields as $k => $picfield) {
                    if ($trRow[$picfield] != "") {
                        $json['data'][$picfield] = Library::getTruckSellUploadLink() . $trRow[$picfield];
                    } else {
                        $json['data'][$picfield] = "";
                    }
                }
            } else {
                $json['data'] = array();
            }
        }
        //echo '<pre>';print_r($json);echo '</pre>';exit;
        header('Content-Type: application/json');
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    function getSellTruckDetails($id) {
        return Selltruck::model()->find('id_sell_truck="' . $id . '"');
    }

    public function actionApplySellTruck() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/ApplySellTruck?uid=1&accountid=santosh&id_sell_truck=1&expected_price=1000
          method=post
          params=uid,accountid,id_sell_truck,expected_price
         */

        $json = array("status" => 0);

        //if (Yii::app()->request->isPostRequest) {
        $data['id_customer'] = (int) $_REQUEST['uid'];
        $data['gps_account_id'] = $_REQUEST['accountid'];
        $data['id_sell_truck'] = (int) $_REQUEST['id_sell_truck'];
        $data['expected_price'] = $_REQUEST['expected_price'];
        $data['date_created'] = new CDbExpression('NOW()');
        if (1) {
            $Selltruckinterested = new Selltruckinterested;
            $Selltruckinterested->attributes = $data;
            if ($Selltruckinterested->validate()) {
                $Selltruckinterested->save(false);
                $struckObj = $this->getSellTruckDetails($data['id_sell_truck']);
                $custObj = $this->getCustomer($data['id_customer']);
                $message = "Sell Truck Lead:" . $custObj->fullname . "," . $custObj->mobile . " interested in " . $struckObj->truck_reg_no . " of " . $struckObj->contact_name . " " . $struckObj->contact_mobile;
                $this->sendMail(array("subject" => $message, "message" => $message));
                //$this->sendPushNotification(array("message"=>$message));

                $json['status'] = 1;
            } else {
                $json['status'] = 0;
            }
        }
        echo CJSON::encode($json);
        if ($json['status']) {
            $this->sendPushNotification(array("message" => $message));
        }
        Yii::app()->end();
    }

    public function actionsetDeviceLoad() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/setDeviceLoad?uid=1&cname=santosh&mobile=9988776655&deviceid=AP36k7271&lookingforload=1&address=address&vehicleModel=21 ton
          method=post
         */

        $json = array("status" => 0);
        $data['accountid'] = $_REQUEST['accountid'];
        $data['uid'] = (int) $_REQUEST['uid'];
        $data['deviceid'] = $_REQUEST['deviceid'];
        $data['mobile'] = (int) $_REQUEST['mobile'];
        $data['cname'] = $_REQUEST['cname'];
        $data['address'] = $_REQUEST['address'];
        $data['truck_type'] = $_REQUEST['vehicleModel'];
        $data['lookingforload'] = (int) $_REQUEST['lookingforload'];
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            GpsDeviceLoyalityPoints::model()->deleteAll('deviceID="' . $data['deviceid'] . '" and  activity="lookingForLoad" and accountID="' . $data['accountid'] . '" and date(dateCreated)="' . date('Y-m-d') . '"');
            $obj = new GpsDeviceLoyalityPoints;
            $obj->accountID = $data['accountid'];
            $obj->deviceID = $data['deviceid'];
            $obj->activity = 'lookingForLoad';
            $obj->dateCreated = new CDbExpression('NOW()');
            $obj->points = 10;
            $obj->status = 0;
            $obj->save();
            GpsDevice::model()->updateAll(array("lookingForLoadDate" => date('Y-m-d'), "lookingForLoad" => $data['lookingforload']), "lower(deviceID) like '" . strtolower($data['deviceid']) . "'");
            if ($data['lookingforload']) {
                $json['msg'] = "Thank you.will contact you soon.Loyality points will be added after confirmation!!";
                $message = "Looking for Load:Truck No " . $data['deviceid'] . " (" . $data['truck_type'] . ") of " . $data['cname'] . "," . $data['mobile'] . " located at " . $data['address'];
            } else {
                $message = "Not Looking for Load:Truck No " . $data['deviceid'] . " (" . $data['truck_type'] . ") of " . $data['cname'] . "," . $data['mobile'] . " located at " . $data['address'];
            }

            $this->sendMail(array("subject" => $message, "message" => $message));

            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        if ($data['lookingforload']) {
            $this->sendPushNotification(array("message" => $message));
        }
        Yii::app()->end();
    }

    public function actionsetDeviceDetails() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/setDeviceDetails
          method=post
          params=deviceid,accountid,fitnessExpire,insuranceExpire,rcNo,NPAvailable,NPExpire,insuranceAmount

          add param uid
         */

        $json = array("status" => 0);
        $accountid = $_REQUEST['accountid'];
        $deviceid = $_REQUEST['deviceid'];
        $fitnessExpiry = $_REQUEST['fitnessExpire'];
        $insuranceExpiry = $_REQUEST['insuranceExpire'];
        $rcNo = $_REQUEST['rcNo'];
        $NPAvailable = $_REQUEST['NPAvailable'];
        $NPExpiry = $_REQUEST['NPExpire'];
        $insuranceAmount = $_REQUEST['insuranceAmount'];
        //if (Yii::app()->request->isPostRequest) {
        if (1) {
            $json['status'] = 1;
            GpsDevice::model()->updateAll(array("fitnessExpire" => $fitnessExpiry, "insuranceExpire" => $insuranceExpiry, "rcNo" => $rcNo, "NPAvailable" => $NPAvailable, "NPExpire" => $NPExpiry, "insuranceAmount" => $insuranceAmount), "deviceID='" . $deviceid . "' and accountID='" . $accountid . "'");
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionrepost() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/repost
          method=post
          params=id_load_truck_request,uid
          status=0,1
          1:success
          0:faile
         */
        $json = array('status' => 1);

        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actiongetPostLoadDetails() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/getPostLoadDetails?id_load_truck_request=
          method=post
          params=id_load_truck_request
         */
        $json = array("status" => 0);
        $id = (int) $_REQUEST['id_load_truck_request'];
        if (1) {
            $trRow = Yii::app()->db->createCommand("select * from eg_load_truck_request where id_load_truck_request='" . $id . "'")->queryRow();
            $json['data'] = $trRow;
            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actiongetLoyalityPointsInfo() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/getLoyalityPointsInfo?uid=1
          method=post
         */
        $uid = (int) $_REQUEST['uid'];
        $json = array("status" => 0);
        if (1) {
            $json['status'] = 1;
            $custRow = Yii::app()->db->createCommand("select loyality_points from eg_customer where id_customer='" . $uid . "'")->queryRow();
            $json['data']['your_points'] = $custRow['loyality_points'];

            $giftRows = Yii::app()->db->createCommand("select * from eg_loyality_gifts where status=1 order by points asc")->queryAll();
            foreach ($giftRows as $giftRow) {
                $json['data']['gifts'][] = array("points" => $giftRow['points'], "image" => Library::getLoyalityGiftsUploadLink() . $giftRow['image'], "lid" => $giftRow['id_loyality_gifts']);
            }
            /* $json['data']['gifts'][]=array("points"=>6000,"image"=>Library::getLoyalityGiftsUploadLink()."bike.png","lid"=>1);
              $json['data']['gifts'][]=array("points"=>8000,"image"=>Library::getLoyalityGiftsUploadLink()."card.png","lid"=>2);
              $json['data']['gifts'][]=array("points"=>15000,"image"=>Library::getLoyalityGiftsUploadLink()."mobile.png","lid"=>3);
              $json['data']['gifts'][]=array("points"=>20000,"image"=>Library::getLoyalityGiftsUploadLink()."tv.png","lid"=>4);
              $json['data']['gifts'][]=array("points"=>30000,"image"=>Library::getLoyalityGiftsUploadLink()."gps.png","lid"=>5); */
            $json['data']['content'][] = "1)	Now earn 10 reward points every time you update the vehicle status or request for load through our mobile application.";
            $json['data']['content'][] = "2)	Our call centre executive will confirm the availability of vehicles and any wrong updations will be cancelled and are not eligible for rewards. EasyGaadi decision is final and binding.";
            $json['data']['content'][] = "3)	You can redeem the rewards for exciting gifts by clicking here.";
            $json['data']['content'][] = "4)	The minimum points required to redeem is 5000 points.";
            $json['data']['content'][] = "5)	Points expire once every 1 year.";
            $json['data']['content'][] = "6)	Please contact our customer care for any issues with credit of reward points";
            $json['data']['terms'] = "Terms & Conditions Apply";
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionredeemPoints() {
        /*
          egcrm.cloudapp.net/operations/index.php/GPSapiV3/redeemPoints?uid=2&lid=1&points=600
          method=post
          params=uid,lid,points

         */
        $uid = (int) $_REQUEST[uid];
        $lid = (int) $_REQUEST[lid];
        $redeem_points = $_REQUEST[points];
        $json = array("status" => 0);
        if (1) {
            //Customer::model()->updateAll('loyality_points=""');
            Yii::app()->db->createCommand("update eg_customer set loyality_points=loyality_points-" . $redeem_points . " where id_customer='" . $uid . "'")->query();
            $model = new Customerpointsredeem;
            $model->id_customer = $uid;
            $model->id_loyality_gifts = $lid;
            $model->received_gift = 0;
            $model->date_created = new CDbExpression('NOW()');
            $model->save();
            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function sendPushNotification($data) {

        //Library::sendSingleSms(array('to'=>'9573369999','message'=>$data['message']));
        //Library::sendSingleSms(array('to'=>'9867488800','message'=>$data['message']));
        /*
          $userList="'8008009988'";//comma separated list of userName ex:'santosh','ameet'
          $row=Yii::app()->db->createCommand("select group_concat(device_id) as dev from eg_gps_login_device where username in (".$userList.")")->queryRow();

          $devices=array();
          if($row!=""){
          $devices=explode(",",$row['dev']);
          Library::sendPushNotification(array('devices'=>$devices,'message'=>array('message'=>$data['message'],'type'=>'load')));
          }
          //echo $row['dev']."<pre>";print_r($devices);exit;
         */
    }

    public function actioncreateTrip() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/createTrip
          method=post
          params=Trip[deviceID],Trip[accountID],Trip[source],Trip[destination]
         */

        $json = array("status" => 0);
        $trip = $_POST['Trip'];
        if (Yii::app()->request->isPostRequest) {
            $deviceObj = GpsDevice::model()->find(array("select" => "lastValidLatitude,lastValidLongitude", "condition" => "accountID='" . $trip['accountID'] . "' and deviceID='" . $trip['deviceID'] . "'"));
            $model = new GpsTrip;
            $model->attributes = $trip;
            $model->source = str_replace(', India', '', $trip['source']);
            $model->destination = str_replace(', India', '', $trip['destination']);
            $model->startPointLat = $deviceObj->lastValidLatitude;
            $model->startPointLng = $deviceObj->lastValidLongitude;
            $model->startPointTime = time();
            $model->dateTimeCreated = date('Y-m-d H:i:s');
            if ($model->validate()) {
                $model->save(false);
                $json['status'] = 1;
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actiongetTripList() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/getTripList
          method=post
          params=Trip[accountID],Trip[deviceID],Trip[offset]
         */
        $trip = $_POST['Trip'];
        $json = array("status" => 0);
        if (Yii::app()->request->isPostRequest) {
            /* $tripsObj=GpsTrip::model()->findAll("accountID='".$trip['accountID']."' order by dateTimeCreated desc limit ".$this->limit." offset ".(int)$trip[offset]); */

            $srchQry = "";
            if ($trip['q'] != "") {
                $srchQry = "deviceID like '%" . $trip['q'] . "%' and ";
            }
            $tripsObj = GpsTrip::model()->findAll($srchQry . "accountID='" . $trip['accountID'] . "' order by dateTimeCreated desc limit " . $this->limit . " offset " . (int) $trip[offset]);
            $tripCountQry = GpsTrip::model()->find(array("select" => "count(*) as id", "condition" => $srchQry . "accountID='" . $trip['accountID'] . "'"));
            $json['count'] = $tripCountQry->id;

            $i = 0;
            foreach ($tripsObj as $tripObj) {
                $json['data'][$i] = $tripObj->getAttributes();
                $addrArr = Library::getGPBYLATLNGDetailsCloud($tripObj->startPointLat . "," . $tripObj->startPointLng);
                $json['data'][$i]['startLoc'] = $addrArr['address'];
                if (!$tripObj->endPointTime) {
                    $mDevice = GpsDevice::model()->find(array('select' => 'lastValidLatitude,lastValidLongitude', 'condition' => "accountID='" . $trip['accountID'] . "' and deviceID='" . $tripObj->deviceID . "'"));
                    //echo $mDevice->lastValidLatitude.",".$mDevice->lastValidLongitude."accountID='".$trip['accountID']."' and deviceID='".$trip['deviceID']."'";exit;
                    $addrArr = Library::getGPBYLATLNGDetailsCloud($mDevice->lastValidLatitude . "," . $mDevice->lastValidLongitude);
                    $json['data'][$i]['currentLoc'] = $addrArr['address'];
                } else {
                    $addrArr = Library::getGPBYLATLNGDetailsCloud($tripObj->endPointLat . "," . $tripObj->endPointLng);
                    $json['data'][$i]['endLoc'] = $addrArr['address'];
                }
                $i++;
            }
            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actiongetTripSummary() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/getTripSummary
          method=post
          params=Trip[id]
         */
        $trip = $_POST['Trip'];
        $json = array("status" => 0);
        if (Yii::app()->request->isPostRequest) {
            $tripObj = GpsTrip::model()->find("id=" . (int) $trip[id]);
            $json[data] = $tripObj->getAttributes();
            $addrArr = Library::getGPBYLATLNGDetailsCloud($tripObj->startPointLat . "," . $tripObj->startPointLng);
            $json['data']['startLoc'] = $addrArr['address'];
            if (!$tripObj->endPointTime) {
                $mDevice = GpsDevice::model()->find(array('select' => 'lastValidLatitude,lastValidLongitude', 'condition' => "accountID='" . $tripObj->accountID . "' and deviceID='" . $tripObj->deviceID . "'"));
                $addrArr = Library::getGPBYLATLNGDetailsCloud($mDevice->lastValidLatitude . "," . $mDevice->lastValidLongitude);
                $json['data']['currentLoc'] = $addrArr['address'];
            }

            $days29 = 2505600;
            if ($tripObj->startPointTime + $days29 > time()) {
                $return = $this->gettriptrack(array("accountID" => $tripObj->accountID, "deviceID" => $tripObj->deviceID, "startPointTime" => $tripObj->startPointTime, "endPointTime" => $tripObj->endPointTime));
                $json['data']['speedLimit'] = $return['data']['speedLimit'];
                $json['data']['stops'] = $return['data']['stops'];
                $json['data']['topSpeed'] = $return['data']['topSpeed'];
                $json['data']['points'] = $return['data']['points'];
                if (!$tripObj->endPointTime) {
                    $json['data']['odo'] = $return['data']['odo'];
                    $json['data']['hoursTravelled'] = $return['data']['hoursTravelled'];
                    $json['data']['avgSpeed'] = $return['data']['avgSpeed'];
                } else {
                    $addrArr = Library::getGPBYLATLNGDetailsCloud($tripObj->endPointLat . "," . $tripObj->endPointLng);
                    $json['data']['endLoc'] = $addrArr['address'];
                }
                //echo '<pre>';print_r($json);print_r($json1);exit;
            }
            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function gettriptrack($input) {
        $to = $input[endPointTime] != 0 ? $input[endPointTime] : time();
        $query = "SELECT ifnull(latitude,0) as latitude, ifnull(longitude,0) as longitude, speedKPH, (ifnull(timestamp,0)+19800) as time_in_secs, FROM_UNIXTIME(ifnull(timestamp,0), '%Y %D %M %k:%i') as date_time,heading,odometerKM,distanceKM FROM EventData WHERE accountID like '" . $input[accountID] . "' and  deviceID like '" . $input[deviceID] . "' AND timestamp <=" . $to . " AND timestamp >=" . $input[startPointTime] . "  ORDER BY timestamp";

        $rows = Yii::app()->db_gts->createCommand($query)->queryAll();
        $rowAccount = Yii::app()->db_gts->createCommand("select stopDurationLimit,overSpeedLimit from Account where accountID='" . $input[accountID] . "'")->queryRow();
        $stopDurationLimit = $rowAccount[stopDurationLimit] == 0 ? '300' : $rowAccount[stopDurationLimit] * 60;
        $overSpeedLimit = $rowAccount[overSpeedLimit] == 0 ? '70' : $rowAccount[overSpeedLimit];

        $distancetravelled = 0;
        $speedsum = 0;
        $points = array();
        $stops = array();
        $loop = 0;
        $stopTime = 0;
        $stopLoop = 0;
        $topSpeed = 0;
        $i = 0;
        $startStopNew = 1;
        $startingStopTime = 0;
        foreach ($rows as $row) {
            if ($row['speedKPH']) {
                $topSpeed = $row['speedKPH'] > $topSpeed ? $row['speedKPH'] : $topSpeed;
                $distancetravelled+=$row['distanceKM'];
                $speedsum+=$row['speedKPH'];
                $points[$loop] = $row;
                $points[$loop]['speed'] = ceil($row['speedKPH']) . " Km/Hr";
                $points[$loop]['speedValue'] = ceil($row['speedKPH']);
                $loop++;
            } else {

                if ($startStopNew) {
                    $startingStopTime = $row['time_in_secs'];
                    $startStopNew = 0;
                }

                if ($rows[$i + 1]['speedKPH']) {
                    $stopTime = $rows[$i + 1]['time_in_secs'] - $startingStopTime;
                    if ($stopTime > $stopDurationLimit) {
                        $stops[$stopLoop] = array("latitude" => $row['latitude'], "longitude" => $row['longitude'], "date_time" => $row['date_time'], "time_in_secs" => $startingStopTime, "stopTime" => $stopTime, "distance" => 0);
                        $stopLoop++;
                    }
                    $startStopNew = 1;
                }
            }
            $i++;
        }
        //lastPoint
        if (is_array($rows[$i - 1])) {
            $points[$loop] = $rows[$i - 1];
            $points[$loop]['speed'] = ceil($rows[$i - 1]['speedKPH']) . " Km/Hr";
            $points[$loop]['speedValue'] = ceil($rows[$i - 1]['speedKPH']);
        }

        $avgspeed = $speedsum / $loop;
        $distancetravelled = ceil($distancetravelled);
        $avgspeed = ceil($avgspeed);
        $timeTravelled = floor($distancetravelled / $avgspeed);
        $idleTime = floor((($too - $fromm) - ($timeTravelled * 3600)) / 3600);

        if (is_array($points) && sizeof($points)) {
            $json['statusCode'] = 1;
            //$json['success']['odokm']=ceil($info[max_odokm]-$info[min_odokm])." Km";
            $json['data']['speedLimit'] = $overSpeedLimit;
            $json['data']['stops'] = $stops;
            $json['data']['topSpeed'] = ceil($topSpeed) . " Km/Hr";
            $json['data']['odo'] = $distancetravelled; //." Km";
            $json['data']['hoursTravelled'] = $timeTravelled; //." Hours";
            $json['data']['avgSpeed'] = $avgspeed; //." Km/Hr";
            $json['data']['points'] = $points;
        }
        return $json;
    }

    public function actiondeleteTrip() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/deleteTrip
          method=post
          params=Trip[id],Trip[accountID],Trip[deviceID]
         */
        $trip = $_POST['Trip'];
        $json = array("status" => 0);
        if (Yii::app()->request->isPostRequest) {
            GpsTrip::model()->deleteAll('id="' . (int) $trip['id'] . '"');
            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionstopTrip() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/stopTrip
          method=post
          params=Trip[id],Trip[accountID],Trip[deviceID],Trip[startPointTime]
         */
        $trip = $_POST['Trip'];
        $json = array("status" => 0);
        if (Yii::app()->request->isPostRequest) {
            $deviceObj = GpsDevice::model()->find(array("select" => "lastValidLatitude,lastValidLongitude,lastGPSTimestamp", "condition" => "accountID='" . $trip['accountID'] . "' and deviceID='" . $trip['deviceID'] . "'"));

            $query = "SELECT speedKPH,distanceKM FROM EventData where accountID like '" . $trip['accountID'] . "' AND deviceID like '" . $trip['deviceID'] . "' AND timestamp <= " . $deviceObj->lastGPSTimestamp . " AND timestamp >=" . $trip['startPointTime'] . "  ORDER BY timestamp";
            $rows = Yii::app()->db_gts->createCommand($query)->queryAll();
            $loop = 0;
            $distancetravelled = 0;
            foreach ($rows as $row) {
                if ($row['speedKPH']) {
                    $topSpeed = $row['speedKPH'] > $topSpeed ? $row['speedKPH'] : $topSpeed;
                    $distancetravelled+=$row['distanceKM'];
                    $speedsum+=$row['speedKPH'];
                    $loop++;
                }
            }
            $avgspeed = $speedsum / $loop;
            $timeTravelled = floor($distancetravelled / $avgspeed);

            GpsTrip::model()->updateAll(array('endPointLat' => $deviceObj->lastValidLatitude, 'endPointLng' => $deviceObj->lastValidLongitude, 'endPointTime' => $deviceObj->lastGPSTimestamp, 'odo' => $distancetravelled, 'hoursTravelled' => $timeTravelled, 'avgSpeed' => $avgspeed), 'id="' . (int) $trip['id'] . '"');
            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actiongetGroupList() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/getGroupList
          method=post
          params=account_id
         */
        $accountID = $_REQUEST['account_id'];
        $json = array("status" => 0);
        //if(($accountID!="") && (Yii::app()->request->isPostRequest)){
        if ($accountID != "") {
            $rows = Yii::app()->db_gts->createCommand("select groupID,displayName,contactName,contactPhone,password from DeviceGroup where accountID='" . $accountID . "'")->queryAll();
            $json['redirect'] = 1;
            $json['data'][] = array("id" => "0", "groupname" => "All");
            foreach ($rows as $row) {
                $json['redirect'] = 0;
                $json['data'][] = array("id" => $row['groupID'], "groupname" => $row['displayName'], "contactName" => $row['contactName'], "contactPhone" => $row['contactPhone'], "password" => $row['password']);
            }

            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionSetPassword() { //need to upload image
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/SetPassword
          method=post
          params=password,accountid,uid
          status=0,1
          1:success
          0:faile
          new param uid
         */

        $json = array("status" => 1);
        $password = $_REQUEST['password'];
        $accountid = $_REQUEST['accountid'];
        $uid = (int) $_REQUEST['uid'];
        //if (Yii::app()->request->isPostRequest) {
        if ($password != "" && ($accountid != "" || $uid != "")) {
            $encpassword = Admin::hashPassword($password);
            if ($accountid != "") {
                Yii::app()->db_gts->createCommand("update Account set password='" . $password . "' where accountID='" . $accountid . "'")->query();
                Yii::app()->db->createCommand("update {{customer}} set password='" . $encpassword . "' where gps_account_id='" . $accountid . "'")->query();
                $json["status"] = 1;
            } else if ($uid != 0) {
                Yii::app()->db->createCommand("update {{customer}} set password='" . $encpassword . "' where id_customer='" . $uid . "'")->query();
                $json["status"] = 1;
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionORoutes() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/oroutes
          method=post
          params=accountid,source,destination,type=get/set
         */

        $json = array("status" => 0);
        $accountid = $_POST['accountid'];
        $source = $_POST['source'];
        $destination = $_POST['destination'];
        $type = $_POST['type'];
        //echo $type;
        //exit;
        if (Yii::app()->request->isPostRequest) {
            if ($type == 'set') {
                GpsAccountOperatingDestinations::model()->deleteAll('accountID="' . $accountid . '"');
                foreach ($source as $k => $v) {
                    if ($v == '' || $destination[$k] == '') {
                        continue;
                    }

                    $gDetails1 = Library::getGPDetails($v);
                    $gDetails2 = Library::getGPDetails($destination[$k]);

                    $model['cod'] = new GpsAccountOperatingDestinations;
                    $model['cod']->accountID = $accountid;

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
            } else if ($type == 'get') {
                $rows = GpsAccountOperatingDestinations::model()->findAll(array("select" => "source_address,destination_address", "condition" => "accountID='" . $accountid . "'"));

                //echo '<pre>';print_r($rows);echo '</pre>';
                //exit("here");
                foreach ($rows as $k => $row) {
                    $json['data'][] = array('source' => $row['source_address'], 'destination' => $row['destination_address']);
                }
            }
            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionSettings() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/Settings
          method=post
          params=accountid,emailDailSummaryReport,routeNotificationCount,stopAlertTime,overSpeedLimit,contactEmail,stopDurationLimit,source,destination,type=get/set
         */

        $json = array("status" => 0);
        $accountid = $_POST['accountid'];
        $emailDailSummaryReport = $_POST['emailDailSummaryReport'];
        $routeNotificationCount = $_POST['routeNotificationCount'];
        $stopAlertTime = $_POST['stopAlertTime'];
        $overSpeedLimit = $_POST['overSpeedLimit'];
        $contactEmail = $_POST['contactEmail'];
        $stopDurationLimit = $_POST['stopDurationLimit'];
        $type = $_POST['type'];

        $source = $_POST['source'];
        $destination = $_POST['destination'];

        //echo $type;
        //exit;
        if (Yii::app()->request->isPostRequest) {
            if ($type == 'set') {
                GpsAccount::model()->updateAll(array('emailDailSummaryReport' => $emailDailSummaryReport,
                    'routeNotificationCount' => $routeNotificationCount,
                    'stopAlertTime' => $stopAlertTime,
                    'overSpeedLimit' => $overSpeedLimit,
                    'contactEmail' => $contactEmail,
                    'stopDurationLimit' => $stopDurationLimit), "accountID='" . $accountid . "'");

                GpsAccountOperatingDestinations::model()->deleteAll('accountID="' . $accountid . '"');
                foreach ($source as $k => $v) {
                    if ($v == '' || $destination[$k] == '') {
                        continue;
                    }

                    $gDetails1 = Library::getGPDetails($v);
                    $gDetails2 = Library::getGPDetails($destination[$k]);

                    $model['cod'] = new GpsAccountOperatingDestinations;
                    $model['cod']->accountID = $accountid;

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
            } else if ($type == 'get') {
                $trRow = Yii::app()->db_gts->createCommand("select emailDailSummaryReport,routeNotificationCount,stopAlertTime,overSpeedLimit,contactEmail,stopDurationLimit from Account where accountID='" . $accountid . "'")->queryRow();
                $json['settings'] = $trRow;

                $rows = GpsAccountOperatingDestinations::model()->findAll(array("select" => "source_address,destination_address", "condition" => "accountID='" . $accountid . "'"));

                //echo '<pre>';print_r($rows);echo '</pre>';
                //exit("here");
                foreach ($rows as $k => $row) {
                    $json['routes'][] = array('source' => $row['source_address'], 'destination' => $row['destination_address']);
                }
            }
            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionCreateGroup() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/CreateGroup
          method=post
          params=accountid,displayName,contactName,contactPhone,password,deviceID
         */

        $json = array("status" => 0);
        $accountid = $_POST['accountid'];
        $contactName = $_POST['contactName'];
        $displayName = $_POST['displayName'];
        $contactPhone = $_POST['contactPhone'];
        $password = $_POST['password'];
        $deviceID = $_POST['deviceID'];
        if (Yii::app()->request->isPostRequest) {
            $total = Yii::app()->db_gts->createCommand("select count(*) as total from DeviceGroup where accountID='" . $accountid . "' and contactPhone='" . $contactPhone . "'")->queryScalar();
            if (!$total) {
                $groupID = Yii::app()->db_gts->createCommand("select max(groupID)+1 as groupID from DeviceGroup")->queryScalar();
                $DeviceGroup = new GpsDeviceGroup;
                $DeviceGroup->accountID = $accountid;
                $DeviceGroup->groupID = $groupID;
                $DeviceGroup->contactName = $contactName;
                $DeviceGroup->contactPhone = $contactPhone;
                $DeviceGroup->password = $password;
                $DeviceGroup->displayName = $displayName;
                $DeviceGroup->creationTime = strtotime('now');
                $DeviceGroup->lastUpdateTime = strtotime('now');
                $DeviceGroup->save(false);
                if (is_array($deviceID)) {
                    foreach ($deviceID as $k => $v) {
                        GpsDevice::model()->updateAll(array("groupID" => $groupID), 'deviceID="' . $v . '" and accountID="' . $accountid . '"');
                    }
                }
                $json['status'] = 1;
                $json['msg'] = 'Group Created Successfully!';
            } else {
                $json['status'] = -1;
                $json['msg'] = 'User Already Exist.Contact Phone should be unique!';
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actiongetDevices() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/getDevices
          method=post
          params=accountid,
         */

        $json = array("status" => 0);
        $accountid = $_POST['accountid'];
        if (Yii::app()->request->isPostRequest) {
            $rows = GpsDevice::model()->findAll(array("select" => "deviceID,groupID", "condition" => "isActive=1 and accountID='" . $accountid . "'"));
            foreach ($rows as $k => $row) {
                $json['data'][] = array('deviceID' => $row['deviceID'], 'groupID' => (int) $row['groupID']);
            }
            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionassignDevices() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/assignDevices
          method=post
          params=groupID,accountID,displayName,contactName,contactPhone,password,deviceID
         */

        $json = array("status" => 0);
        $accountID = $_POST['accountid'];
        $groupID = $_POST['groupID'];
        $displayName = $_POST['displayName'];
        $contactName = $_POST['contactName'];
        $contactPhone = $_POST['contactPhone'];
        $password = $_POST['password'];
        $deviceID = $_POST['deviceID'];
        if (Yii::app()->request->isPostRequest) {
            GpsDeviceGroup::model()->updateAll(array('displayName' => $displayName,
                'contactName' => $contactName,
                'contactPhone' => $contactPhone,
                'password' => $password), "groupID='" . $groupID . "'");
            //exit('groupID="'.$groupID.'" and accountID="'.$accountID.'"');
            GpsDevice::model()->updateAll(array("groupID" => ''), 'groupID="' . $groupID . '" and accountID="' . $accountID . '"');
            if (is_array($deviceID)) {
                foreach ($deviceID as $k => $v) {
                    GpsDevice::model()->updateAll(array("groupID" => $groupID), 'deviceID="' . $v . '" and accountID="' . $accountID . '"');
                }
            }
            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actiongetDistanceReport() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/getDistanceReport
          method=post
          params=accountID
         */

        $json = array("status" => 0);
        $accountID = $_POST['accountID'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        if (Yii::app()->request->isPostRequest && $from != "" && $to != "" && $accountID != "") {
            $devices = GpsDevice::model()->findAll(array('select' => 'deviceID', 'condition' => 'isActive=1 and accountID="' . $accountID . '"'));
            $json['data'] = array();
            foreach ($devices as $device) {
                $event = GpsEventData::model()->find(array('select' => 'sum(distanceKM) as statusCode', 'condition' => 'deviceID="' . $device->deviceID . '" and accountID="' . $accountID . '" and (timestamp <= unix_timestamp("' . $to . '") AND timestamp >=unix_timestamp("' . $from . '"))'));
                $json['data'][] = array('deviceID' => $device->deviceID, 'km' => round($event->statusCode, 2),'startLocation'=>'Hyderabad, Telangana','endLocation'=>'Pune, Maharastra');
            }
            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

    public function actionDeviceDetails() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/DeviceDetails
          method=post
          params=deviceID,accountID,nextGreasingDate,lastGreasingDate,greasingKm,nextServicingDate,lastServicingDate,servicingKm,eOilExpiryDate,eOilReplacedDate,eOilKm,driverName,driverMobile,nPDoc,nPExpire,insuranceDoc,insuranceExpire,fitnessDoc,fitnessExpire,rcExpire,rcDoc,pollDoc,pollExpire,driverLicenceNo,driverOnDuty,Tyres[level],Tyres[position],Tyres[expiryDate],Tyres[installDate],Tyres[km]
         */

        $json = array("status" => 0);
        $accountID = $_POST['accountID'];
        $deviceID = $_POST['deviceID'];
        $type = $_POST['type'];

        $nextGreasingDate = $_POST['nextGreasingDate'];
        $lastGreasingDate = $_POST['lastGreasingDate'];
        $greasingKm = $_POST['greasingKm'];
        $nextServicingDate = $_POST['nextServicingDate'];
        $lastServicingDate = $_POST['lastServicingDate'];
        $servicingKm = $_POST['servicingKm'];
        $eOilExpiryDate = $_POST['eOilExpiryDate'];
        $eOilReplacedDate = $_POST['eOilReplacedDate'];
        $eOilKm = $_POST['eOilKm'];
        $driverName = $_POST['driverName'];
        $driverMobile = $_POST['driverMobile'];
        $nPDoc = $_POST['nPDoc'];
        $nPExpire = $_POST['nPExpire'];
        $insuranceDoc = $_POST['insuranceDoc'];
        $insuranceExpire = $_POST['insuranceExpire'];
        $fitnessDoc = $_POST['fitnessDoc'];
        $fitnessExpire = $_POST['fitnessExpire'];
        $rcExpire = $_POST['rcExpire'];
        $rcDoc = $_POST['rcDoc'];
        $pollDoc = $_POST['pollDoc'];
        $pollExpire = $_POST['pollExpire'];
        $driverLicenceNo = $_POST['driverLicenceNo'];
        $driverOnDuty = $_POST['driverOnDuty'];

        $tyres = $_POST['Tyres'];


        if (Yii::app()->request->isPostRequest && $accountID != "" && $deviceID != "") {
            if ($type == 'set') {
                $dmData = GpsDeviceMaintenance::model()->find(array('select' => 'nPDoc,insuranceDoc,fitnessDoc,rcDoc,pollDoc', 'condition' => 'accountID="' . $accountID . '" and deviceID="' . $deviceID . '"'));

                $field = 'nPDoc';
                $data = "";
                $data = $_FILES[$field];
                $data['input']['prefix'] = $field . '_' . $deviceID . '_';
                $data['input']['path'] = Library::getTruckUploadPath();
                $data['input']['prev_file'] = $dmData->$field;
                $upload = Library::fileUpload($data);
                $nPDoc = $upload['file'];

                $field = 'insuranceDoc';
                $data = "";
                $data = $_FILES[$field];
                $data['input']['prefix'] = $field . '_' . $deviceID . '_';
                $data['input']['path'] = Library::getTruckUploadPath();
                $data['input']['prev_file'] = $dmData->$field;
                $upload = Library::fileUpload($data);
                $insuranceDoc = $upload['file'];

                $field = 'fitnessDoc';
                $data = "";
                $data = $_FILES[$field];
                $data['input']['prefix'] = $field . '_' . $deviceID . '_';
                $data['input']['path'] = Library::getTruckUploadPath();
                $data['input']['prev_file'] = $dmData->$field;
                $upload = Library::fileUpload($data);
                $fitnessDoc = $upload['file'];

                $field = 'rcDoc';
                $data = "";
                $data = $_FILES[$field];
                $data['input']['prefix'] = $field . '_' . $deviceID . '_';
                $data['input']['path'] = Library::getTruckUploadPath();
                $data['input']['prev_file'] = $dmData->$field;
                $upload = Library::fileUpload($data);
                $rcDoc = $upload['file'];


                $field = 'pollDoc';
                $data = "";
                $data = $_FILES[$field];
                $data['input']['prefix'] = $field . '_' . $deviceID . '_';
                $data['input']['path'] = Library::getTruckUploadPath();
                $data['input']['prev_file'] = $dmData->$field;
                $upload = Library::fileUpload($data);
                $pollDoc = $upload['file'];


                GpsDeviceMaintenance::model()->updateAll(array('nextGreasingDate' => $nextGreasingDate, 'lastGreasingDate' => $lastGreasingDate, 'greasingKm' => $greasingKm, 'nextServicingDate' => $nextServicingDate, 'lastServicingDate' => $lastServicingDate, 'servicingKm' => $servicingKm, 'eOilExpiryDate' => $eOilExpiryDate, 'eOilReplacedDate' => $eOilReplacedDate, 'eOilKm' => $eOilKm, 'driverName' => $driverName, 'driverMobile' => $driverMobile, 'nPDoc' => $nPDoc, 'nPExpire' => $nPExpire, 'insuranceDoc' => $insuranceDoc, 'insuranceExpire' => $insuranceExpire, 'fitnessDoc' => $fitnessDoc, 'fitnessExpire' => $fitnessExpire, 'rcExpire' => $rcExpire, 'rcDoc' => $rcDoc, 'pollDoc' => $pollDoc, 'pollExpire' => $pollExpire, 'driverLicenceNo' => $driverLicenceNo, 'driverLicenceNo' => $driverLicenceNo, 'driverOnDuty' => $driverOnDuty), 'accountID="' . $accountID . '" and deviceID="' . $deviceID . '"');

                GpsDeviceTyres::model()->deleteAll('accountID="' . $accountID . '" and deviceID="' . $deviceID . '"');
                if (is_array($tyres)) {
                    foreach ($tyres as $k=>$tyre) {
                        $model = new GpsDeviceTyres();
                        $model->attributes = $tyre;
                        $model->accountID = $accountID;
                        $model->deviceID = $deviceID;
                        if ($model->validate()) {
                            $model->save(false);
                        }
                    }
                }
            } else if ($type == 'get') {

                $row = GpsDeviceMaintenance::model()->find('accountID="' . $accountID . '" and deviceID="' . $deviceID . '"');
                //$json['data']=$row->getAttributes();
                //echo '<pre>';print_r($row);exit("here");
                if (is_object($row)) {
                    $data = $row->getAttributes();
					$json['data'] = $data;
					$filePath=Library::getTruckUploadPath();
					$fileLink=Library::getTruckUploadLink();
                    $json['data']['nPDoc']=($data['nPDoc']!="" && file_exists($filePath.$data['nPDoc']))?$fileLink.$data['nPDoc']:'';
                    $json['data']['insuranceDoc']=($data['insuranceDoc']!="" && file_exists($filePath.$data['insuranceDoc']))?$fileLink.$data['insuranceDoc']:'';
                    $json['data']['fitnessDoc']=($data['fitnessDoc']!="" && file_exists($filePath.$data['fitnessDoc']))?$fileLink.$data['fitnessDoc']:'';
                    $json['data']['rcDoc']=($data['rcDoc']!="" && file_exists($filePath.$data['rcDoc']))?$fileLink.$data['rcDoc']:'';
                    $json['data']['pollDoc']=($data['pollDoc']!="" && file_exists($filePath.$data['pollDoc']))?$fileLink.$data['pollDoc']:'';
                } else {
                    $model = new GpsDeviceMaintenance;
                    $model->accountID = $accountID;
                    $model->deviceID = $deviceID;
                    $model->save(false);
                    $row = GpsDeviceMaintenance::model()->find('accountID="' . $accountID . '" and deviceID="' . $deviceID . '"');
                    $json['data'] = $row->getAttributes();
                }

                $tyres = GpsDeviceTyres::model()->findAll('accountID="' . $accountID . '" and deviceID="' . $deviceID . '"');
                $json['data']['tyres'] = array();
                foreach ($tyres as $tyre) {
                    $json['data']['tyres'][] = $tyre;
                }
            }
            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function actionShareYourVehicle() {
	/*
	  http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/ShareYourVehicle
	  method=post
	  params
	  type=get,accountID
	  
	  type=set,accountID,deviceID,source,destination
	 */

	$json = array("status" => 0);
	$accountID = $_POST['accountID'];
	$deviceID = $_POST['deviceID'];
	$source = $_POST['source'];
	$destination = $_POST['destination'];
	$type = $_POST['type'];

	//echo $type;
	//exit;
	if (Yii::app()->request->isPostRequest) {
		if ($type == 'set') {

				$model = new GpsDeviceShare;
				$model->accountID = $accountID;
				$model->deviceID = $deviceID;
				$model->source = $source;
				$model->destination=$destination;
				$model->save(false);
		} else if ($type == 'get') {
			$rows = GpsDeviceShare::model()->findAll(array("select" => "source,destination,DATE_FORMAT( dateCreated,  '%d-%m-%Y' ) as dateCreated,link,deviceID", "condition" => "accountID='" . $accountID . "' order by dateCreated desc"));

			//echo '<pre>';print_r($rows);echo '</pre>';
			//exit("here");
                        $json['data']=array();
			foreach ($rows as $k => $row) {
				$json['data'][$k]['deviceID'] = $row['deviceID'];
                                $json['data'][$k]['source'] = $row['source'];
                                $json['data'][$k]['destination'] = $row['destination'];
                                $json['data'][$k]['dateCreated'] = $row['dateCreated'];
				$json['data'][$k]['link'] = 'http://www.easygaadi.com';
				//$json['data'][$k]['msg'] = $row->deviceID.' : '.$row->source.' - '.$row->destination;
                                $json['data'][$k]['msg'] = $row['deviceID'].' : '.$row['source'].' - '.$row['destination'];
			}
		}
		$json['status'] = 1;
	}
	echo CJSON::encode($json);
	Yii::app()->end();  
    }

	public function actiongetDashboard() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV3/getDashboard
          method=Post
          params=accountID,access_token
         */

        $json = array("status" => 0);
        $accountID = $_POST['accountID'];
        //$access_token = $_POST['access_token'];
        
	//$this->validateToken();    
        if (Yii::app()->request->isPostRequest) {
     			$json['data']['servicingExpiry'][]=array('deviceID'=>'120DAYS','serviceOn'=>'2017-03-15','currentKm'=>1000,'expiryKm'=>1000,'expired'=>0);
			$json['data']['servicingExpiry'][]=array('deviceID'=>'30DAYS','serviceOn'=>'2017-03-14','currentKm'=>1000,'expiryKm'=>1000,'expired'=>1);

			$json['data']['eOilExpiry'][]=array('deviceID'=>'120DAYS','expiryOn'=>'2017-03-15','currentKm'=>1000,'expiryKm'=>1000,'expired'=>0);
			$json['data']['eOilExpiry'][]=array('deviceID'=>'30DAYS','expiryOn'=>'2017-03-14','currentKm'=>1000,'expiryKm'=>1000,'expired'=>1);

			$json['data']['tyresExpiry'][]=array('deviceID'=>'120DAYS','level'=>'Front','position'=>'L1','expiryOn'=>'2017-03-15','currentKm'=>1000,'expiryKm'=>1000,'expired'=>0);
			$json['data']['tyresExpiry'][]=array('deviceID'=>'30DAYS','level'=>'Rear','position'=>'L2','expiryOn'=>'2017-03-14','currentKm'=>1000,'expiryKm'=>1000,'expired'=>1);

			$json['data']['nPermitExpiry'][]=array('deviceID'=>'120DAYS','expiryOn'=>'2017-03-15','expired'=>0);
			$json['data']['nPermitExpiry'][]=array('deviceID'=>'30DAYS','expiryOn'=>'2017-03-14','expired'=>1);

			$json['data']['rCExpiry'][]=array('deviceID'=>'120DAYS','expiryOn'=>'2017-03-15','expired'=>0);
			$json['data']['rCExpiry'][]=array('deviceID'=>'30DAYS','expiryOn'=>'2017-03-14','expired'=>1);

			$json['data']['pollutionExpiry'][]=array('deviceID'=>'120DAYS','expiryOn'=>'2017-03-15','expired'=>0);
			$json['data']['pollutionExpiry'][]=array('deviceID'=>'30DAYS','expiryOn'=>'2017-03-14','expired'=>1);

			$json['data']['fitnessExpiry'][]=array('deviceID'=>'120DAYS','expiryOn'=>'2017-03-15','expired'=>0);
			$json['data']['fitnessExpiry'][]=array('deviceID'=>'30DAYS','expiryOn'=>'2017-03-14','expired'=>1);

			$json['data']['insuranceExpiry'][]=array('deviceID'=>'120DAYS','expiryOn'=>'2017-03-15','expired'=>0);
			$json['data']['insuranceExpiry'][]=array('deviceID'=>'30DAYS','expiryOn'=>'2017-03-14','expired'=>1);

            $json['status'] = 1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
}

<?php

class DcapiController extends Controller {

    public $layout = "//layouts/guest";
    public $limit=5;

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
    
    public function actionForgotpassword() {
		
	$json=array("status"=>0);
        $username=$_POST['username'];
	if (Yii::app()->request->isPostRequest && $username!="") {
	    $user=Admin::model()->find('id_admin_role="11" and status=1 and LOWER(email)=?',array(strtolower($username)));
            if($user===null){
		$json['status']=0;
		}else if($user->id_admin){
                $json['status']=1;
                $password=Library::randomPassword();
                $user->model()->updateAll(array('password'=>Admin::hashPassword($password)),'id_admin='.$user->id_admin);
                Library::sendSingleSms(array('to'=>$user->phone,'message'=>'password:'.$password));
            }else{
                $json['status']=0;
            }
	}
        //echo '<pre>';print_r($json);echo '</pre>';
        echo CJSON::encode($json);
	Yii::app()->end();
    }
 
    public function actionLogin() {
		
		//echo '<pre>';print_r(Yii::app()->db);echo '</pre>';
        //Yii::app()->db['enableProfiling']=false;
        $json=array("status"=>0);
		//$_POST['username']="muntaz@easygaadi.com";
        //$_POST['password']="123456";//;
        if (Yii::app()->request->isPostRequest && $_POST['username']!="" && $_POST['password']!="") {
			//if(1){
            $username=$_POST['username'];
            $password=$_POST['password'];
            $user=Admin::model()->find('id_admin_role="11" and status=1 and LOWER(email)=?',array(strtolower($username)));
            if($user===null){
				$json['status']=0;
			}else if(!$user->validatePassword($password)){
                $json['status']=0;
            }else{
                $json['status']=1;
                $json['data']=array('id_admin'=>$user->id_admin,'first_name'=>$user->first_name,'last_name'=>$user->last_name,'phone'=>$user->phone,'email'=>$user->email,'city'=>$user->city,'state'=>$user->state);
                Yii::app()->db->createCommand('UPDATE {{admin}} SET last_visit_date = present_visit_date,present_visit_date = NOW( ) WHERE id_admin ="'.$user->id_admin.'"')->query();
            }
	}
        //echo '<pre>';print_r($json);echo '</pre>';
        echo CJSON::encode($json);
		Yii::app()->end();
		
    }
    
    
    public function actionGetLeads(){
        $json=array("status"=>0);
        $id=(int)$_REQUEST['id'];
        $offset=(int)$_REQUEST['offset'];
        //if (Yii::app()->request->isPostRequest) {
		if (1) {
            
            $sql="select c.*,cl.lead_status,cl.lead_source,clsh.message from {{customer}} c,{{customer_lead}} cl,{{customer_access_permission}} cap,{{customer_lead_status_history}} clsh where cap.id_admin='".$id."'  and cap.id_customer=c.id_customer and cap.id_customer=cl.id_customer and c.islead=1 and clsh.id_customer=cap.id_customer";// and clsh.status='Document Collection'";
            $count=Yii::app()->db->createCommand("select count(*) from (".$sql.") as tab")->queryScalar();
            $rows = Yii::app()->db->CreateCommand($sql." order by date_created desc limit ".$this->limit." offset ".$offset)->queryAll();
                $json['status']=1;
                $json['data']=$rows;
                $json['count']=$count;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
     public function actionGetTruckTypes(){
        $rows=Yii::app()->db->createCommand('select id_truck_type,concat(title," ",tonnes) as title from {{truck_type}} where status=1')->queryAll();
        $return=array();
        foreach($rows as $row){
            $return[]=array('Key'=>$row['id_truck_type'],'Value'=>$row['title']);
        }
        echo CJSON::encode($return);
        Yii::app()->end();
    }
    
     public function actionGetCustomerTypes(){
        $rows=Library::getCustomerTypes();
        $return=array();
        foreach($rows as $k=>$v){
			if($k=='G'){ continue;}
            $return[]=array('Key'=>$k,'Value'=>$v);
        }
        echo CJSON::encode($return);
        Yii::app()->end();
    }
    
     public function actionGetLeadSources(){
        $rows=Library::getLeadSources();
        $return=array();
        foreach($rows as $k=>$v){
            $return[]=array('Key'=>$k,'Value'=>$v);
        }
        echo CJSON::encode($return);
        Yii::app()->end();
    }
    
    public function actionGetMakeMonths(){
        $rows=Library::getMakeMonths();
        echo CJSON::encode($rows);
        Yii::app()->end();
    }
    
    public function actionGetMakeYears(){
        $rows=Library::getMakeYears();
        echo CJSON::encode($rows);
        Yii::app()->end();
    }
    
    /*public function actionGetCustomerDocTypes(){
        $rows=Library::getCustomerDocTypes();
        echo CJSON::encode($rows);
        Yii::app()->end();
    }*/

	 public function actionGetCustomerDocTypes(){
        $rows=Library::getCustomerDocTypes();
        $return=array();
        foreach($rows as $k=>$v){
            $return[]=array('Key'=>$k,'Value'=>$v);
        }
        echo CJSON::encode($return);
        Yii::app()->end();
    }
    
     public function actionGetPaymentTypes(){
        $rows=Library::getPaymentTypes();
        $return=array();
        foreach($rows as $k=>$v){
            $return[]=array('Key'=>$k,'Value'=>$v);
        }
        echo CJSON::encode($return);
        Yii::app()->end();
    }
    
     public function actionGetLeadStatuses(){
        $rows=Library::getLeadStatuses();
        foreach($rows as $k=>$v){
            $return[]=array('Key'=>$k,'Value'=>$v);
        }
        echo CJSON::encode($return);
        Yii::app()->end();
    }
    
    
     public function actionGetStates(){
        $rows=Library::getStates();
        foreach($rows as $k=>$v){
            $return[]=array('Key'=>$k,'Value'=>$v);
        }
        echo CJSON::encode($return);
        Yii::app()->end();
    }
    
    public function actionGetYearsInService(){
        $rows=Library::getExperienceYear();
        echo CJSON::encode($rows);
        Yii::app()->end();
    }
    
    public function actionGetGoodsType(){
        $rows=Yii::app()->db->createCommand('select id_goods_type,title from {{goods_type}} where status=1')->queryAll();
        $return=array();
        foreach($rows as $row){
            $return[]=array('Key'=>$row['id_goods_type'],'Value'=>$row['title']);
        }
        echo CJSON::encode($return);
        Yii::app()->end();
    }

	public function actionGetLead(){
		$json=array();
		$json['status']=0;
        $id=(int)$_REQUEST['id'];
		$type=$_REQUEST['type'];
        //if (Yii::app()->request->isPostRequest) {
			if (1) {
				switch($type){
					case 'leadStatus':
						$json['status']=1;
						$json['data']=Yii::app()->db->createCommand("select clsh.*,a.first_name,a.last_name,ar.role from {{customer_lead_status_history}} clsh,{{admin}} a,{{admin_role}} ar where  clsh.id_customer='".$id."' and clsh.id_admin=a.id_admin and a.id_admin_role=ar.id_admin_role order by date_created desc")->queryAll();
                                            break;
					
                                        case 'accessPermissions';
					    $json['status']=1; 
                                            $json['data']=Yii::app()->db->createCommand("select a.first_name,a.last_name,ar.role from {{customer_access_permission}} cap,{{admin}} a,{{admin_role}} ar where  cap.id_customer='".$id."' and cap.id_admin=a.id_admin and a.id_admin_role=ar.id_admin_role")->queryAll();
                                            break;
                                        
                                        case 'drivers';
					    $json['status']=1; 
                                            $json['data']=Yii::app()->db->createCommand("select d.* from {{driver}} d,{{customer_driver_current}} cdc where cdc.id_customer='".$id."' and cdc.id_driver=d.id_driver")->queryAll();
                                            break;
                                            
                                         case 'info';
					    $json['status']=1; 
                                            $json['data']=Yii::app()->db->createCommand("select (select group_concat(cvt.title) from {{customer_vechile_types}} cvt where cvt.id_customer=c.id_customer)  as trucktype,c.*,cl.lead_status,cl.lead_source from {{customer}} c,{{customer_lead}} cl where c.id_customer=cl.id_customer and c.id_customer='".$id."'")->queryRow();
                                            break;
                                            
                                        case 'docs';
					    $json['status']=1; 
                                            $json['data']=Yii::app()->db->createCommand("select * from {{customer_docs}} where id_customer='".$id."'")->queryAll();
                                            break;
                                    case 'optDest';
					    $json['status']=1; 
                                            $json['data']=Yii::app()->db->createCommand("select * from {{customer_operating_destinations}} where id_customer='".$id."'")->queryAll();
                                            break;
                                    case 'truckTypes';
					    $json['status']=1; 
                                            $json['data']=Yii::app()->db->createCommand("select * from {{customer_vechile_types}} where id_customer='".$id."'")->queryAll();        
                                            break;
                                        
                                    case 'trucks';
					    $json['status']=1; 
                                            //$json['data']=Yii::app()->db->createCommand("select * from {{truck}} where id_customer='".$id."'")->queryAll();        
					$json['data']=Yii::app()->db->createCommand("select tr.*,concat(tt.title,' ',tt.tonnes) as truck_type_title from {{truck}} tr,{{truck_type}} tt where tr.id_truck_type=tt.id_truck_type and tr.id_customer='".$id."'")->queryAll();
											
                                            break;    
				}
			}
        echo CJSON::encode($json);
        Yii::app()->end();
	}
    
    public function actionGetLead1(){
        $json=array("status"=>0);
        $id=(int)$_POST['id'];
        if (Yii::app()->request->isPostRequest) {
             
            $rows['customer']=Yii::app()->db->createCommand("select c.*,cl.lead_status,cl.lead_source from {{customer}} c,{{customer_lead}} cl where c.id_customer=cl.id_customer and c.id_customer='".$id."'")->queryRow();

            $rows['accessPermissions']=Yii::app()->db->createCommand("select a.first_name,a.last_name,ar.role from {{customer_access_permission}} cap,{{admin}} a,{{admin_role}} ar where  cap.id_customer='".$id."' and cap.id_admin=a.id_admin and a.id_admin_role=ar.id_admin_role")->queryAll();

            $rows['docs']=Yii::app()->db->createCommand("select * from {{customer_docs}} where id_customer='".$id."'")->queryAll();

            $rows['drivers']=Yii::app()->db->createCommand("select d.* from {{driver}} d,{{customer_driver_current}} cdc where cdc.id_customer='".$id."' and cdc.id_driver=d.id_driver")->queryAll();

            $rows['leadStatus']=Yii::app()->db->createCommand("select clsh.*,a.first_name,a.last_name,ar.role from {{customer_lead_status_history}} clsh,{{admin}} a,{{admin_role}} ar where  clsh.id_customer='".$id."' and clsh.id_admin=a.id_admin and a.id_admin_role=ar.id_admin_role order by date_created desc")->queryAll();

            $rows['optDest']=Yii::app()->db->createCommand("select * from {{customer_operating_destinations}} where id_customer='".$id."'")->queryAll();

            $rows['truckTypes']=Yii::app()->db->createCommand("select * from {{customer_vechile_types}} where id_customer='".$id."'")->queryAll();

            $rows['trucks']=Yii::app()->db->createCommand("select * from {{truck}} where id_customer='".$id."'")->queryAll();
            $json['status']=1;
            $json['data']=$rows;
            $json['count']=$count;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    
    
    public function actionGetInBoundUser(){
        $json=array("status"=>0);
        if (Yii::app()->request->isPostRequest) {
                $row = Yii::app()->db->CreateCommand("select a.id_admin,(select count(*) from {{customer}} c,{{customer_access_permission}} cl where c.id_customer=cl.id_customer and c.islead=1 and cl.id_admin=a.id_admin and cl.status=1 and c.date_created>'" . date('Y-m-d', strtotime("-2 days")) . "') as rows from {{admin}} a where a.status=1 and a.id_admin_role=9 order by rows asc")->queryRow();
                $json['status']=1;
                $json['id_admin']=$row['id_admin'];
                
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actionSetDriver(){ //need to upload image
        /*
	http://egcrm.cloudapp.net/operations/index.php/dcapi/SetDriver
	method=post
	params=id,id_driver,name,mobile,licence
        Note:if id_driver is empty then it will create driver.if id_driver exists then it will update existing driver. 
	*/

        //id,id_driver
        $id=(int)$_REQUEST['id'];
        $id_driver=(int)$_REQUEST['id_driver'];
        $json=array("status"=>0);
        $name=$_REQUEST['name'];
        $mobile=$_REQUEST['mobile'];
        $licence=$_REQUEST['licence'];
        if (Yii::app()->request->isPostRequest) {
            $json['status']=1;
            if(!$id_driver){ //create
                   $drObj=new Driver;
                   $drObj->name=$name;
                   $drObj->mobile=$mobile;
                   //$drObj->licence_pic=$upload['file'];
                   $drObj->date_created=new CDbExpression('NOW()');
                   $drObj->save(false);
                   $cdc=new Customerdrivercurrent;
                   $cdc->id_customer=$id;
                   $cdc->id_driver=$drObj->id_driver;
                   $cdc->save(false);

                   $cdh=new Customerdriverhistory;
                   $cdh->id_customer=$id;
                   $cdh->id_driver=$drObj->id_driver;
                   $cdh->save(false);
                                        
            }else{ //update
                Driver::model()->updateAll(array("name"=>$name,"mobile"=>$mobile,"licence_pic"=>$licence),"id_driver='".$id_driver."'");
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actionSetTruck(){ //need to delete image
        /*
	http://egcrm.cloudapp.net/operations/index.php/dcapi/SetTruck
	method=post
	params=id,id_truck,truck_reg_no,id_truck_type,make_year,make_month,tracking_available,insurance_available,fitness_certificate_expiry_date,vehicle_insurance_expiry_date,description,fitness_certification,vehicle_insurance,vehicle_rc,back_pic,left_pic,right_pic,front_pic,top_pic
        Note:if id_truck is empty then it will create truck.if id_truck exists then it will update existing truck 
        fitness certificate expiry date and vehicle insurance expiry date should be in this format 2015-10-30 ie YYYY-MM-DD    
         *          */
        $id=$_REQUEST['id'];
        $id_truck=$_REQUEST['id_truck'];
        $truck_reg_no=$_REQUEST['truck_reg_no'];
        $id_truck_type=$_REQUEST['id_truck_type'];
        $make_year=$_REQUEST['make_year'];
        $make_month=$_REQUEST['make_month']<10?"0".$_REQUEST['make_month']:$_REQUEST['make_month'];
        $tracking_available=$_REQUEST['tracking_available'];
        $insurance_available=$_REQUEST['insurance_available'];
        $fitness_certificate_expiry_date=$_REQUEST['fitness_certificate_expiry_date'];
        $vehicle_insurance_expiry_date=$_REQUEST['vehicle_insurance_expiry_date'];
        $description=$_REQUEST['description'];
		$gps_mobile_no=$_REQUEST['gps_mobile_no'];
		$gps_imei_no=$_REQUEST['gps_imei_no'];
        $fitness_certification;
        $vehicle_insurance;
        $vehicle_rc;
        $back_pic;
        $left_pic;
        $right_pic;
        $front_pic;
        $top_pic;
        
        $id_truck=(int)$_REQUEST['id_truck'];
        $json=array("status"=>0);
        //if (Yii::app()->request->isPostRequest) {
          if (1) {
			$json=array("status"=>1);
            if(!$id_truck){ //create
                $model=new Truck;
                $model->id_customer=$id;
                $model->date_created=new CDbExpression('NOW()');
                $model->truck_reg_no=$truck_reg_no;
                $model->id_truck_type=$id_truck_type;
                $model->make_year=$make_year;
                $model->make_year=$make_year;
                $model->tracking_available=$tracking_available;
                $model->insurance_available=$insurance_available;
                $model->fitness_certificate_expiry_date=$fitness_certificate_expiry_date;
                $model->vehicle_insurance_expiry_date=$vehicle_insurance_expiry_date;
                $model->description=$description;
                $model->fitness_certificate=$fitness_certification;
                $model->vehicle_insurance=$vehicle_insurance;
                $model->vehicle_rc=$vehicle_rc;
				$model->gps_imei_no=$gps_imei_no;
				$model->gps_mobile_no=$gps_mobile_no;
                $model->back_pic=$back_pic;
                $model->left_pic=$left_pic;
                $model->right_pic=$right_pic;
                $model->front_pic=$front_pic;
                $model->top_pic=$top_pic;
                $model->approved=1;
                $model->status=1;
                $model->save(false);
            }else{ //update
                $data=array('gps_mobile_no'=>$gps_mobile_no,'gps_imei_no'=>$gps_imei_no,'id_customer'=>$id,'truck_reg_no'=>$truck_reg_no,'id_truck_type'=>$id_truck_type,'make_year'=>$make_year,'make_month'=>$make_month,'tracking_available'=>$tracking_available,'insurance_available'=>$insurance_available,'fitness_certificate_expiry_date'=>$fitness_certificate_expiry_date,'vehicle_insurance_expiry_date'=>$vehicle_insurance_expiry_date,'description'=>$description,'fitness_certification'=>$fitness_certification,'vehicle_insurance'=>$vehicle_insurance,'vehicle_rc'=>$vehicle_rc,'back_pic'=>$back_pic,'left_pic'=>$left_pic,'right_pic'=>$right_pic,'front_pic'=>$front_pic,'top_pic'=>$top_pic);
				//echo '<pre>';print_r($data);echo '</pre>';
                Truck::model()->updateAll($data,'id_truck="'.$id_truck.'"');
            }                                
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actionDeleteDriver(){ //need to delete image
        /*
	http://egcrm.cloudapp.net/operations/index.php/dcapi/DeleteDriver
	method=post
	params=id_driver
        Note:it will delete particular driver. 
	*/
        
        $id_driver=(int)$_REQUEST['id_driver'];
        $json=array("status"=>0);
        if (Yii::app()->request->isPostRequest) {
            $json=array("status"=>1);
            Driver::model()->deleteAll('id_driver="'.$id_driver.'"');
            Customerdrivercurrent::model()->deleteAll('id_driver="'.$id_driver.'"');
            Customerdriverhistory::model()->deleteAll('id_driver="'.$id_driver.'"');
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actionDeleteTruck(){ //need to delete image
        /*
	http://egcrm.cloudapp.net/operations/index.php/dcapi/DeleteTruck
	method=post
	params=id_truck
        Note:it will delete particular truck. 
	*/
        
        $id_truck=(int)$_REQUEST['id_truck'];
        $json=array("status"=>0);
        if (Yii::app()->request->isPostRequest) {
            $json=array("status"=>1);
            Truck::model()->deleteAll('id_truck="'.$id_truck.'"');
            Truckdoc::model()->deleteAll('id_truck="'.$id_truck.'"');
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actionSetInfo(){
        /*http://egcrm.cloudapp.net/operations/index.php/dcapi/SetInfo
	method=post
	params=id_admin,id,type,fullname,email,mobile,id_truck_type,no_of_vechiles,year_in_service,alt_mobile_1,alt_mobile_2,alt_mobile_3,company,address,operating_source_city,city,state,landline,payment_type,bank_name,bank_account_no,bank_ifsc_code,bank_branch,lead_source
        Note:if id is null then record will be created else record will be updated*/
        $json=array("status"=>0);
        $data=array();
        $id_admin=(int)$_REQUEST['id_admin'];
        $data[id]=(int)$_REQUEST['id'];
        $data[type]=$_REQUEST['type'];
        $data[fullname]=$_REQUEST['fullname'];
        $data[email]=$_REQUEST['email'];
        $data[mobile]=$_REQUEST['mobile'];
        $data[id_truck_type]=$_REQUEST['id_truck_type'];
        $data[no_of_vechiles]=$_REQUEST['no_of_vechiles'];
        $data[year_in_service]=$_REQUEST['year_in_service'];
        $data[alt_mobile_1]=$_REQUEST['alt_mobile_1'];
        $data[alt_mobile_2]=$_REQUEST['alt_mobile_2'];
        $data[alt_mobile_3]=$_REQUEST['alt_mobile_3'];
        $data[company]=$_REQUEST['company'];
        $data[address]=$_REQUEST['address'];
        $data[operating_source_city]=$_REQUEST['operating_source_city'];
        $data[city]=$_REQUEST['city'];
        $data[state]=$_REQUEST['state'];
        $data[landline]=$_REQUEST['landline'];
        $data[payment_type]=$_REQUEST['payment_type'];
        $data[bank_name]=$_REQUEST['bank_name'];
        $data[bank_account_no]=$_REQUEST['bank_account_no'];
        $data[bank_ifsc_code]=$_REQUEST['bank_ifsc_code'];
        $data[bank_branch]=$_REQUEST['bank_branch'];
        $data[lead_source]=$_REQUEST['lead_source'];
        
        
        if (Yii::app()->request->isPostRequest) {
            $json=array("status"=>1);
            if(!$id){ //create
                $model=new Customer();
                $model->attributes=$data;
                $model->date_created=new CDbExpression('NOW()');
                $model->save(false);
                
                $modelCl=new Customerlead();
                $modelCl->id_customer=$model->id_customer;
                $modelCl->lead_source=$data[lead_source];
                $modelCl->lead_status='Initiated';//$data[lead_status];
                $modelCl->id_admin_created=$id_admin;
                $modelCl->save(false);
                $data[id]=$model->id_customer;
                $this->addAccessHistory(array('message'=>'Created Lead','id_customer'=>$model->id_customer,'id_admin'=>$id_admin));    
                $this->addAccessPermission(array('id_customer'=>$model->id_customer,'id_admin'=>$id_admin));    
                $this->addLeadStatus(array('message'=>'Created Lead,Need to collect docs!','status'=>'Initiated','id_customer'=>$model->id_customer,'id_admin'=>$id_admin));
            }else{ //update
                Customer::model()->updateAll($data,'id_customer="'.$data[id].'"');
            }
            $this->addTruckTypes(array('id_customer'=>$data[id],'id_truck_type'=>$data['id_truck_type']));
        }
        
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function addTruckTypes($input){
	if($input['id_truck_type']!=""){
		Customervechiletypes::model()->deleteAll("id_customer='".$input['id_customer']."'");
		$exp=explode(",",$input['id_truck_type']);
		foreach($exp as $k=>$v){
			if($v==""){ continue;}
			$getCustData=Trucktype::model()->find("id_truck_type='".$v."'");
			$obj=new Customervechiletypes;
			$obj->id_customer=$input['id_customer'];
			$obj->title=$getCustData->title;
			$obj->tonnes=$getCustData->tonnes;
			$obj->id_truck_type=$getCustData->id_truck_type;
			$obj->save(false);	
		}
	}
    }
    
        public function addAccessHistory($input){
	$obj=new Customeraccesshistory;
	$obj->id_customer=$input['id_customer'];
	$obj->id_admin=$input['id_admin'];
	$obj->message=$input['message'];
	$obj->save(false);
    }
    
    public function addAccessPermission($input){
        $obj=new Customeraccesspermission;
        $obj->id_admin=$input['id_admin'];
        $obj->id_customer = $input['id_customer'];
        $obj->date_created = date('Y-m-d');
        $obj->save(false);
    }

    public function  addLeadStatus($input)
    {
        $obj=new Customerleadstatushistory;
        $obj->id_admin=$input['id_admin'];
        $obj->id_customer=$input['id_customer'];
        $obj->message=$input['message'];
        $obj->status=$input['status'];
        $obj->save(false);
    }

	public function actionSetOperatingRoutes(){
    /*
	http://egcrm.cloudapp.net/operations/index.php/dcapi/SetOperatingRoutes
	method=post
	params=id,source,destination
    Note:source,destination should be array
	*/
	//echo '<pre>';print_r($_REQUEST);echo '</pre>';//exit;
        $json=array();
        $json['status']=0;
        $id=(int)$_REQUEST['id'];
        $source=$_REQUEST['source'];
        $destination=$_REQUEST['destination'];
        //if (Yii::app()->request->isPostRequest && $id!=0) {
		if ($id!=0) {
            $json['status']=1;
            Customeroperatingdestinations::model()->deleteAll('id_customer="'.$id.'"');
            foreach($source as $k=>$v){
                $gDetails1=Library::getGPDetails($v);
                $gDetails2=Library::getGPDetails($destination[$k]);
                $model=new Customeroperatingdestinations;
                $model->id_customer = $id;
                $model->source_city = $gDetails1['city'] == '' ? $gDetails1['input'] : $gDetails1['city'];
                $model->source_address = $gDetails1['input'];
                $model->source_state = $gDetails1['state'];
                $model->source_lat = $gDetails1['lat'];
                $model->source_lng = $gDetails1['lng'];
                $model->destination_city = $gDetails2['city'] == '' ? $gDetails2['input'] : $gDetails2['city'];
                $model->destination_address = $gDetails2['input'];
                $model->destination_state = $gDetails2['state'];
                $model->destination_lat = $gDetails2['lat'];
                $model->destination_lng = $gDetails2['lng'];
                $model->save(false);
				//echo '<pre>';print_r($model->getAttributes());print_r($gDetails1);print_r($gDetails2);echo '</pre>';
            }
        }    
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actionFileUpload(){
        /**
        http://egcrm.cloudapp.net/operations/index.php/dcapi/FileUpload
	method=post
	params
          type=truck or driver or customer
          field=field for which you are uploading
          file=current uploading file
          prev_file=existing file
          id=that particular record id
         
         truck:fitness_certificate,vehicle_insurance,vehicle_rc,back_pic,left_pic,top_pic,right_pic,front_pic
         driver:licence_pic
         customer:tds_declaration_doc,profile_image,Pan Card,Driving Licence,Voter Card,Adhaar Card,Ration Card,Electricity Bill,Bank Pass Book
         */
        
        $type=$_REQUEST['type']; //truck or driver or customer
        $field=$_REQUEST['field'];
        $file=$_REQUEST['file'];
        $prev_file=$_REQUEST['prev_file'];
        $id=(int)$_REQUEST['id'];
        $json=array("status"=>0);
        //if (Yii::app()->request->isPostRequest && $id!=0) {
	if (!$id) {
            $data=array();
            $data = $_FILES['file'];
            $data['input']['prev_file'] = $prev_file;
            
            if($upload['status']){
                switch($type){
                    case 'driver':
                                $data['input']['prefix'] = 'driver_' . $id . '_';
                                $data['input']['path'] = Library::getMiscUploadPath();
                                $upload = Library::fileUpload($data);
                                Driver::model()->updateAll(array($field=>$upload['file']),'id_driver="'.$id.'"');
                                break;
                    case 'truck':
                                $data['input']['prefix'] = $field.'_' . $id . '_';
                                $data['input']['path'] = Library::getTruckUploadPath();
                                $upload = Library::fileUpload($data);
                                Truck::model()->updateAll(array($field=>$upload['file']),'id_truck="'.$id.'"');
                                break;
                    case 'customer':
                                $data['input']['prefix'] = 'profile_' . $id . '_';
                                $data['input']['path'] = Library::getMiscUploadPath();
                                $upload = Library::fileUpload($data);
                                Customer::model()->updateAll(array($field=>$upload['file']),'id_customer="'.$id.'"');
                                break;        
                }
                
                $json['status']=1;
            }
            echo CJSON::encode($json);
            Yii::app()->end();
        }
    }

	public function actionDeleteLead(){
    /*
	http://egcrm.cloudapp.net/operations/index.php/dcapi/DeleteLead
	method=post
	params=id
        */
	//echo '<pre>';print_r($_REQUEST);echo '</pre>';//exit;
        $json=array();
        $json['status']=0;
        $id=(int)$_REQUEST['id'];
        //if (Yii::app()->request->isPostRequest && $id!=0) {
	if ($id!=0) {
            $json['status']=1;
            $criteria = new CDbCriteria;
            $criteria->condition='id_customer="'.$id.'"';

            //delete profile images
            $custObjs = Customer::model()->findAll($criteria);
            foreach ($custObjs as $custObj) {
                unlink(Library::getMiscUploadPath() . $custObj->tds_declaration_doc);
                unlink(Library::getMiscUploadPath() . $custObj->profile_image);
            }
            
            $custDocsObjs = Customerdocs::model()->findAll($criteria);
            foreach ($custDocsObjs as $custDocsObj) {
                unlink(Library::getMiscUploadPath() . $custDocsObj->file);
            }
            
            $custDHObjs = Customerdriverhistory::model()->findAll($criteria);
            foreach ($custDHObjs as $custDHObj) {
                $driObj=Driver::model()->find('id_driver="'.$custDHObj->id_driver.'"');
                unlink(Library::getMiscUploadPath() . $driObj->licence_pic);
                Driver::model()->deleteAll('id_driver="'.$custDHObj->id_driver.'"');
            }
            
            $trucksObj = Truck::model()->findAll($criteria);
            foreach ($trucksObj as $truckObj) {
                unlink(Library::getTruckUploadPath() . $truckObj->vehicle_insurance);
                unlink(Library::getTruckUploadPath() . $truckObj->fitness_certificate);
                unlink(Library::getTruckUploadPath() . $truckObj->vehicle_rc);
                unlink(Library::getTruckUploadPath() . $truckObj->front_pic);
                unlink(Library::getTruckUploadPath() . $truckObj->back_pic);
                unlink(Library::getTruckUploadPath() . $truckObj->left_pic);
                unlink(Library::getTruckUploadPath() . $truckObj->rigth_pic);
                unlink(Library::getTruckUploadPath() . $truckObj->top_pic);
                
                $docObjs = Truckdoc::model()->findAll('id_truck="' . $truckObj->id_truck . '"');
                foreach ($docObjs as $docObj) {
                    unlink(Library::getTruckUploadPath() . $docObj->file);
                }
            }

            CActiveRecord::model('Customer')->deleteAll($criteria);
            CActiveRecord::model('Customerdocs')->deleteAll($criteria);
            CActiveRecord::model('Truck')->deleteAll($criteria);
            CActiveRecord::model('Truckrouteprice')->deleteAll($criteria);
            CActiveRecord::model('Customeraccesshistory')->deleteAll($criteria);
            CActiveRecord::model('Customeraccesspermission')->deleteAll($criteria);
            CActiveRecord::model('Customerdrivercurrent')->deleteAll($criteria);
            CActiveRecord::model('Customerdriverhistory')->deleteAll($criteria);
            CActiveRecord::model('Customerlead')->deleteAll($criteria);
            CActiveRecord::model('Customerleadstatushistory')->deleteAll($criteria);
            CActiveRecord::model('Customeroperatingdestinations')->deleteAll($criteria);
            //CActiveRecord::model('Customertruckattachmentpolicy')->deleteAll($criteria);
            CActiveRecord::model('Customervechiletypes')->deleteAll($criteria);
            
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actionLeadUpdateStatus() {
	/*
	http://egcrm.cloudapp.net/operations/index.php/dcapi/LeadUpdateStatus
	method=post
	id,id_admin,message,status
    */

		$json=array();
        $json['status']=0;
        $id=(int)$_REQUEST['id'];
		$id_admin=(int)$_REQUEST['id_admin'];
		$message=$_REQUEST['message'];
		$status=$_REQUEST['status'];
        //if (Yii::app()->request->isPostRequest && $id!=0) {
		if ($id!=0) {
            $obj = new Customerleadstatushistory;
            $obj->id_admin = $id_admin;
            $obj->message = $message;
            $obj->status = $status;
            $obj->id_customer = $id;
            $obj->save(false);
            Customerlead::model()->updateAll(array('lead_status'=>$status),'id_customer='.$id);
            
            $this->addAccessHistory(array('id_admin'=>$id_admin,'id_customer'=>$id,'message'=>'Lead Status Updated to '.$status));
            $json['status']=1;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
	}
}
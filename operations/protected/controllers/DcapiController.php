<?php

class DcapiController extends Controller {

    public $layout = "//layouts/guest";
    public $limit=10;

    public function actions() {
	//Yii::app()->db_gts->enableProfiling=false;		
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
    
    
    public function createCustomer($data){
            //echo '<pre>';print_r($data);exit;		
            if($data['accountID']!="" && $data['password']!="" && $data['contactPhone']!="" && $data['contactName']!=""){
                        $findCObj=Customer::model()->find('mobile="'.$data[contactPhone].'" and type!="G"');
                        $encyPwd=CPasswordHelper::hashPassword($data[password]);
                        if(!is_object($findCObj)){
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
						$idprefix=Library::getIdPrefix(array('id'=>$findCObj->id_customer,'type'=>$findCObj->type));
                        Customer::model()->updateAll(array('idprefix'=>$idprefix,'password'=>$encyPwd,'fullname'=>$data['contactName'],'islead'=>0,'type'=>$data['customer_type'],'gps_account_id'=>Library::trimToLower($data['accountID']),'status'=>$data['isActive'],'approved'=>$data['isActive']),'id_customer="'.$findCObj->id_customer.'"');
						//exit("in else");
					}
                }
}
    
    
    public function actionForgotpassword() {
		
	$json=array("status"=>0);
        $username=$_POST['username'];
	if (Yii::app()->request->isPostRequest && $username!="") {
	    $user=Admin::model()->find('status=1 and LOWER(email)=?',array(strtolower($username)));
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
            //$user=Admin::model()->find('id_admin_role="11" and status=1 and LOWER(email)=?',array(strtolower($username)));//to field team only
            $user=Admin::model()->find('status=1 and LOWER(email)=?',array(strtolower($username)));//to all
            if($user===null){
				$json['status']=0;
			}else if(!$user->validatePassword($password)){
                $json['status']=0;
            }else{
                $json['status']=1;
                $json['data']=array('id_admin'=>$user->id_admin,'first_name'=>$user->first_name,'last_name'=>$user->last_name,'phone'=>$user->phone,'email'=>$user->email,'city'=>$user->city,'state'=>$user->state);
                Yii::app()->db->createCommand('UPDATE {{admin}} SET deviceid="'.$_POST['deviceid'].'",last_visit_date = present_visit_date,present_visit_date = NOW( ) WHERE id_admin ="'.$user->id_admin.'"')->query();
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
		$q=$_REQUEST['q'];
        //if (Yii::app()->request->isPostRequest) {
		if (1) {
            $fltr="";
			if($q!=""){
				$fltr=" and (c.id_customer like '%".$q."%' or c.fullname like '%".$q."%' or c.mobile like '%".$q."%' or c.email like '%".$q."%')";
			}
            $sql="select (select ifnull(group_concat(cd.doc_type),'') from eg_customer_docs cd where cd.id_customer=c.id_customer) as cust_docs,(select ifnull(concat(group_concat(concat(cvt.title,' ',cvt.tonnes) SEPARATOR ', '),','),'') from eg_customer_vechile_types cvt where cvt.id_customer=c.id_customer)  as trucktype,c.*,cl.lead_status,cl.lead_source,clsh.message from eg_customer c,eg_customer_lead cl,eg_customer_access_permission cap,eg_customer_lead_status_history clsh where cap.id_admin='".$id."'  and cap.id_customer=c.id_customer and cap.id_customer=cl.id_customer and c.islead=1 and clsh.id_customer=cap.id_customer".$fltr;// and clsh.status='Document Collection'";
            $countQry=Yii::app()->db->createCommand("select count(*) from (".$sql.") as tab group by id_customer")->queryAll();
			$count=sizeof($countQry);
            $rows = Yii::app()->db->CreateCommand($sql." group by id_customer order by date_created desc limit ".$this->limit." offset ".$offset)->queryAll();
		//echo 	$sql." group by id_customer order by date_created desc limit ".$this->limit." offset ".$offset;
                $json['status']=1;
                $json['data']=$rows;
                $json['count']=$count;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
     public function actionGetTruckTypes(){
        $rows=Yii::app()->db->createCommand('select id_truck_type,concat(title," ",tonnes) as title from {{truck_type}} where status=1 order by tonnes desc')->queryAll();
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
		$chasis_no=$_REQUEST['chasis_no'];
		$engine_no=$_REQUEST['engine_no'];
		$mileage=$_REQUEST['mileage'];
        $fitness_certification;
        $vehicle_insurance;
        $vehicle_rc;
        $back_pic;
        $left_pic;
        $right_pic;
        $front_pic;
        $top_pic;
        //exit("chasis_no".$_REQUEST['chasis_no']." engine_no ".$_REQUEST['engine_no']);
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
				$model->mileage=$mileage;
				
                $model->vehicle_rc=$vehicle_rc;
				$model->gps_imei_no=$gps_imei_no;
				$model->gps_mobile_no=$gps_mobile_no;
				$model->engine_no=$engine_no;
				$model->chasis_no=$chasis_no;
                $model->back_pic=$back_pic;
                $model->left_pic=$left_pic;
                $model->right_pic=$right_pic;
                $model->front_pic=$front_pic;
                $model->top_pic=$top_pic;
                $model->approved=1;
                $model->status=1;
                $model->save(false);
            }else{ //update
                $data=array('mileage'=>$mileage,'engine_no'=>$engine_no,'chasis_no'=>$chasis_no,'gps_mobile_no'=>$gps_mobile_no,'gps_imei_no'=>$gps_imei_no,'id_customer'=>$id,'truck_reg_no'=>$truck_reg_no,'id_truck_type'=>$id_truck_type,'make_year'=>$make_year,'make_month'=>$make_month,'tracking_available'=>$tracking_available,'insurance_available'=>$insurance_available,'fitness_certificate_expiry_date'=>$fitness_certificate_expiry_date,'vehicle_insurance_expiry_date'=>$vehicle_insurance_expiry_date,'description'=>$description,'fitness_certification'=>$fitness_certification,'vehicle_insurance'=>$vehicle_insurance,'vehicle_rc'=>$vehicle_rc,'back_pic'=>$back_pic,'left_pic'=>$left_pic,'right_pic'=>$right_pic,'front_pic'=>$front_pic,'top_pic'=>$top_pic);
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
			
			$driObj=Driver::model()->find('id_driver="'.$id_driver.'"');
			unlink(Library::getMiscUploadPath() . $driObj->licence_pic);
            
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

			$truckObj = Truck::model()->find('id_truck="'.$id_truck.'"');
			unlink(Library::getTruckUploadPath() . $truckObj->vehicle_insurance);
			unlink(Library::getTruckUploadPath() . $truckObj->fitness_certificate);
			unlink(Library::getTruckUploadPath() . $truckObj->vehicle_rc);
			unlink(Library::getTruckUploadPath() . $truckObj->front_pic);
			unlink(Library::getTruckUploadPath() . $truckObj->back_pic);
			unlink(Library::getTruckUploadPath() . $truckObj->left_pic);
			unlink(Library::getTruckUploadPath() . $truckObj->right_pic);
			unlink(Library::getTruckUploadPath() . $truckObj->top_pic);

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
		$data[pincode]=$_REQUEST['pincode'];
		$data[landline]=$_REQUEST['landline'];
        $data[payment_type]=$_REQUEST['payment_type'];
        $data[bank_name]=$_REQUEST['bank_name'];
        $data[bank_account_no]=$_REQUEST['bank_account_no'];
        $data[bank_ifsc_code]=$_REQUEST['bank_ifsc_code'];
        $data[bank_branch]=$_REQUEST['bank_branch'];
        $data[lead_source]=$_REQUEST['lead_source'];
        
        
        if (Yii::app()->request->isPostRequest) {
            $json=array("status"=>1);
            if(!$data[id]){ //create
                $model=new Customer();
                $model->attributes=$data;
                $model->date_created=new CDbExpression('NOW()');
                $adminObj=Admin::model()->find('id_admin='.$id_admin);
                $model->id_franchise=$adminObj->id_franchise;
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
			//echo '<pre>';print_r($_FILES);echo '</pre>';exit;
        $list=array();
        for($i=1;$i<=5;$i++){
            $data=$_FILES['file'.$i];
            if(is_array($data)){
                $nameArr=explode("-",$data['name']);
                $type=$nameArr[0];
                $field=$nameArr[1];
                $id=$nameArr[2];
                switch($type){
                    
                    case 'truck':
                        $truckRow=Yii::app()->db->createCommand('select id_truck,fitness_certificate,vehicle_rc,vehicle_insurance,front_pic,back_pic,left_pic,right_pic,top_pic from eg_truck where id_truck="'.$id.'"')->queryRow();
                        if($truckRow['id_truck']){
                            /*if($truckRow[$field]!=""){ //delete Existing pic
                              @unlink(Library::getTruckUploadPath().$truckRow[$field]);  
                            }*/
                            
                            $data['input']['prefix'] = $field.'_' . $id . '_';
                            $data['input']['path'] = Library::getTruckUploadPath();
                            $data['input']['prev_file'] = $truckRow[$field];
                            $upload = Library::fileUpload($data);
                            if($upload['status']){
                                Truck::model()->updateAll(array($field=>$upload['file']),'id_truck="'.$id.'"');
                            }
                        }
                        break;
                    
                    case 'driver':
                        $driverRow=Yii::app()->db->createCommand('select id_driver,licence_pic from eg_driver where id_driver="'.$id.'"')->queryRow();
                        if($driverRow['id_driver']){
                            
                            $data['input']['prefix'] = $field.'_' . $id . '_';
                            $data['input']['path'] = Library::getMiscUploadPath();
                            $data['input']['prev_file'] = $driverRow[$field];
                            $upload = Library::fileUpload($data);
                            if($upload['status']){
                                Driver::model()->updateAll(array($field=>$upload['file']),'id_driver="'.$id.'"');
                            }
                        }
                        break;
                    
                    case 'customer':
                        $customerRow=Yii::app()->db->createCommand('select id_customer,profile_image,tds_declaration_doc from eg_customer where id_customer="'.$id.'"')->queryRow();
                        if($customerRow['id_customer']){
                            
                            $data['input']['prefix'] = $field.'_' . $id . '_';
                            $data['input']['path'] = Library::getMiscUploadPath();
                            $data['input']['prev_file'] = $customerRow[$field];
                            $upload = Library::fileUpload($data);
                            if($upload['status']){
                                Customer::model()->updateAll(array($field=>$upload['file']),'id_customer="'.$id.'"');
                            }
                        }
                        break;
                        
                    case 'customer_docs':
                        $customerRow=Yii::app()->db->createCommand('select id_customer_docs,id_customer,file from eg_customer_docs where doc_type="'.$field.'" and id_customer="'.$id.'"')->queryRow();
                        if($customerRow['id_customer_docs']){
                            @unlink(Library::getMiscUploadPath().$customerRow['file']);
                            Customerdocs::model()->deleteAll('id_customer_docs="'.$customerRow['id_customer_docs'].'"');
                        }
                        
                        if($id){
                            $data['input']['prefix'] = $field.'_' . $id . '_';
                            $data['input']['path'] = Library::getMiscUploadPath();
                            //$data['input']['prev_file'] = $customerRow['file'];
                            $upload = Library::fileUpload($data);
                            if($upload['status']){
                                $cdObj=new Customerdocs();
                                $cdObj->doc_type=$field;
                                $cdObj->file=$upload['file'];
                                $cdObj->id_customer=$id;
                                $cdObj->save(false);
                            }
                        }
                        break;    
                }
                if($upload['status']){
                   $list[]=$data['name']; 
                }
            }

        }    
        echo CJSON::encode($list);
        Yii::app()->end();
    }
	
	public function actionFileUpload1(){
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
        echo '<pre>';print_r($_FILES);echo '</pre>';EXIT;
        $type=$_REQUEST['type']; //truck or driver or customer
        $field=$_REQUEST['field'];
        $file=$_REQUEST['file'];
        $prev_file=$_REQUEST['prev_file'];
        $id=(int)$_REQUEST['id'];
        $json=array("status"=>0);
        //if (Yii::app()->request->isPostRequest && $id!=0) {
	if ($id) {
            $data=array();
            $data = $_FILES['file'];
            $data['input']['prev_file'] = $prev_file;
            
 
                switch($type){
                    case 'driver':
                                $data['input']['prefix'] = 'driver_' . $id . '_';
                                $data['input']['path'] = Library::getMiscUploadPath();
                                $upload = Library::fileUpload($data);
								//echo '<pre>';print_r($_FILES);print_r($_REQUEST);print_r($upload);echo '</pre>';exit;
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
			$json['msg']=$upload['msg'];
  			//echo '<pre>';print_r($_FILES);print_r($_REQUEST);print_r($upload);echo '</pre>';exit;
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

	public function actionSetGpsTruckInfo(){ //need to upload image
        /*
	http://egcrm.cloudapp.net/operations/index.php/dcapi/SetGpsTruckInfo
	method=post
	params=accountid,address,truck_reg_no,date_available,mobile
        Note:if id_driver is empty then it will create driver.if id_driver exists then it will update existing driver. 
	*/

        $json=array("status"=>0);
	$accountid=$_REQUEST['accountid'];
        $address=$_REQUEST['address'];
        $truck_reg_no=$_REQUEST['truck_reg_no'];
        $mobile=$_REQUEST['mobile'];
        $date_available=$_REQUEST['date_available'];
        
		//if (Yii::app()->request->isPostRequest) {
		if (1) {
            $json['status']=1;
			$model=new Gpstrucklocation;   
			$model->accountid=$accountid;
			$model->address=$address;
			$model->truck_reg_no=$truck_reg_no;
			$model->mobile=$mobile;
			$model->date_available=$date_available;
			$model->save(false);
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actionGetGpsAlerts(){ //need to upload image
    /*
	http://egcrm.cloudapp.net/operations/index.php/dcapi/GetGpsAlerts
	method=get
	param=offset
	*/
        $json=array("status"=>0);
        $offset=(int)$_REQUEST['offset'];
        //if (Yii::app()->request->isPostRequest) {
		if (1) {
            
            $count=Yii::app()->db->createCommand("select count(*) as count from {{gps_alerts}}")->queryScalar();
            $rows = Yii::app()->db->CreateCommand("select * from {{gps_alerts}} order by date_created desc limit ".$this->limit." offset ".$offset)->queryAll();
                $json['status']=1;
                $json['data']=$rows;
                $json['count']=$count;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
	
	public function actionisUserActive(){
        $json=array('status'=>0);
        $id_admin=(int)$_REQUEST['id_admin'];
        //if (Yii::app()->request->isPostRequest) {
		if (1) {
		$row=Admin::model()->find('id_admin='.$id_admin);
        if($row->status){
            $json['status']=1;
        }
		}
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function actionsearchGpsAccount() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/dcapi/searchAccount
          method=post
        */

        $json = array("status" => 0);
        $q = $_POST['q'];
        if (Yii::app()->request->isPostRequest && $q!="") {
            $json['status'] = 1;
            //echo "select accountID,vehicleType,smsEnabled,password,contactName,contactPhone,contactEmail,contactAddress,isActive,stopDurationLimit,overSpeedLimit from Account where accountID like '%".$q."%' or contactPhone like '%".$q."%' or contactName like '%".$q."%' or contactEmail like '%".$q."%' or contactAddress like '%".$q."%'";exit;
            $rows=Yii::app()->db_gts->createCommand("select accountID,vehicleType,smsEnabled,password,contactName,contactPhone,contactEmail,contactAddress,isActive,stopDurationLimit,overSpeedLimit from Account where accountID like '%".$q."%' or contactPhone like '%".$q."%' or contactName like '%".$q."%' or contactEmail like '%".$q."%' or contactAddress like '%".$q."%'")->queryAll();

            foreach($rows as $row){
                    $json['data'][]=$row;
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function actioncreateGpsAccount() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/dcapi/createGpsAccount
          method=post
        */
		$json['status']=0;
        if(Yii::app()->request->isPostRequest)
        {
                $model['ga']=new GpsAccount;
                $model['ga']->attributes=$_POST['GpsAccount'];
                $model['ga']->speedUnits = 1;
                $model['ga']->displayName = $_POST['GpsAccount']['contactName'];
                $model['ga']->distanceUnits = 1;
                $model['ga']->temperatureUnits = 1;
                $model['ga']->currencyUnits = 'INR';
                $model['ga']->allowNotify=1;
                $model['ga']->timeZone = 'IST';
                $model['ga']->geocoderMode = 3;
                $adminObj=Admin::model()->find('id_admin='.$_POST['GpsAccount']['createdById']);
				$model['ga']->retainedEventAge=(int)$adminObj->id_franchise;
				$model['ga']->privateLabelName = '*';
                $model['ga']->description = $_POST['GpsAccount']['contactName'];
                $model['ga']->creationTime = time();
                $model['ga']->accountID =  Library::trimToLower($_POST['GpsAccount']['accountID']);
                if($model['ga']->validate()){
                    $model['ga']->save(false);
                    if($_POST['GpsAccount']['customer_type']!='GPS'){
                        $this->createCustomer($_POST['GpsAccount']);
                    }
                                                $message="Hello ".$_POST['GpsAccount']['contactName'].",You can login now with  Username:".Library::trimToLower($_POST['GpsAccount']['accountID']).",Password:".$_POST['GpsAccount']['password']." .Please download app from https://goo.gl/1dCVYf .Thank you.";
                    $json['status']=1;                            
                    //Library::sendSingleSms(array('to'=>$_POST['GpsAccount']['contactPhone'],'message'=>$message));
                                                
                }else{
                    $errors=$model['ga']->getErrors();
                    foreach($errors as $k=>$v){
                            $json['errors'][]=$v[0];	
                    }
            }
        }

        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actiongetAssignedDevicesByUserId() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/getAssignedDevicesByUserId
          method=post
          params=uid,offset,q,type
        */

        $json = array("status" => 0);
        $uid =(int)$_POST['uid'];
		$q =$_POST['q'];
		$type=$_POST['type']; //open,closed,payment
        $offset = (int)$_POST['offset'];
        if(Yii::app()->request->isPostRequest) {
            $json['status'] = 1;
            
			
			$srchQry="";
			if($q!=""){
				$srchQry=" and (d.accountID like '%".$q."%' or d.deviceID like '%".$q."%' or d.imeiNumber like '%".$q."%' or d.simID like '%".$q."%' or d.simPhoneNumber like '%".$q."%')";
			}

			$typeQry="";
            $selStr="";
			$selsimPN="d.imeiNumber";
			if($type=="open"){
				$typeQry=" and d.devicePaymentStatus=''";
				$selsimPN="concat(d.imeiNumber,' ',FROM_UNIXTIME(ifnull(lastGPSTimestamp,0), '%d-%m-%y %H:%i')) as imeiNumber";
			}else if($type=="payment"){
				$typeQry=" and d.devicePaymentStatus!='' and d.devicePaymentStatus!='Confirmed' ";
                                $selStr='(select a.amount from Accountdeviceplanhistory a where a.deviceID=d.deviceID limit 1) as amount,';
			}else if($type=="closed"){
				$typeQry=" and d.devicePaymentStatus='Confirmed'";
			}
                        //exit("select count(*) as count from Device where installedById='".$uid."'".$srchQry.$typeQry);
                        $total=Yii::app()->db_gts->createCommand("select count(*) as count from Device d where d.installedById='".$uid."'".$srchQry.$typeQry)->queryScalar();
			$json['count']=(int)$total;

                        /*echo "select accountID,deviceID,devicePaymentStatus,isDamaged,vehicleModel,truckTypeId,vehicleType,licenseExpire,insuranceExpire,fitnessExpire,rcNo,NPAvailable,NPExpire,insuranceAmount,simPhoneNumber,simID,imeiNumber,lastValidLatitude,lastValidLongitude,lastValidSpeedKPH,lastGPSTimestamp,lastOdometerKM,isActive,creationTime,lookingForLoad,lookingForLoadDate from Device where installedById='".$uid."' ".$srchQry.$typeQry." order by creationTime desc limit ".$this->limit." offset ".$offset;exit;*/
                        
            $trRows=Yii::app()->db_gts->createCommand("select ".$selStr."d.accountID,d.deviceID,d.devicePaymentStatus,d.isDamaged,d.vehicleModel,d.truckTypeId,d.vehicleType,d.licenseExpire,d.insuranceExpire,d.fitnessExpire,d.rcNo,d.NPAvailable,d.NPExpire,d.insuranceAmount,".$selsimPN.",d.simID,d.simPhoneNumber,d.lastValidLatitude,d.lastValidLongitude,d.lastValidSpeedKPH,FROM_UNIXTIME(ifnull(d.lastGPSTimestamp,0), '%d-%m-%y %H:%i') as lastGpsTimestamp,d.lastOdometerKM,d.isActive,d.creationTime,d.lookingForLoad,d.lookingForLoadDate from Device d  where d.installedById='".$uid."' ".$srchQry.$typeQry." order by d.creationTime desc limit ".$this->limit." offset ".$offset)->queryAll();
            
			if(count($trRows)){
				$i=0;
                foreach($trRows as $trRow){
                    $json['data'][]=$trRow;
					$addrArr=Library::getGPBYLATLNGDetailsCloud($trRow[lastValidLatitude].",".$trRow[lastValidLongitude]);
					if($type=="payment"){
                        $json['data'][$i]['address']=$addrArr['address'];
                    }else{
						$json['data'][$i]['simPhoneNumber']=$trRow['simPhoneNumber']." ".$addrArr['address'];
					}
                    $i++;
                }
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function actiongetAssignedDevicesByUserId1() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/getAssignedDevicesByUserId
          method=post
          params=uid,offset,q,type
        */

        $json = array("status" => 0);
        $uid =(int)$_POST['uid'];
		$q =$_POST['q'];
		$type=$_POST['type']; //open,closed,payment
        $offset = (int)$_POST['offset'];
        if(Yii::app()->request->isPostRequest) {
            $json['status'] = 1;
            
			
			$srchQry="";
			if($q!=""){
				$srchQry=" and (accountID like '%".$q."%' or deviceID like '%".$q."%' or imeiNumber like '%".$q."%' or simID like '%".$q."%' or simPhoneNumber like '%".$q."%')";
			}

			$typeQry="";
			if($type=="open"){
				$typeQry=" and devicePaymentStatus=''";
			}else if($type=="payment"){
				$typeQry=" and devicePaymentStatus!='' and devicePaymentStatus!='Confirmed' ";
			}else if($type=="closed"){
				$typeQry=" and devicePaymentStatus='Confirmed'";
			}
                        //exit("select count(*) as count from Device where installedById='".$uid."'".$srchQry.$typeQry);
                        $total=Yii::app()->db_gts->createCommand("select count(*) as count from Device where installedById='".$uid."'".$srchQry.$typeQry)->queryScalar();
			$json['count']=(int)$total;

                        /*echo "select accountID,deviceID,devicePaymentStatus,isDamaged,vehicleModel,truckTypeId,vehicleType,licenseExpire,insuranceExpire,fitnessExpire,rcNo,NPAvailable,NPExpire,insuranceAmount,simPhoneNumber,simID,imeiNumber,lastValidLatitude,lastValidLongitude,lastValidSpeedKPH,lastGPSTimestamp,lastOdometerKM,isActive,creationTime,lookingForLoad,lookingForLoadDate from Device where installedById='".$uid."' ".$srchQry.$typeQry." order by creationTime desc limit ".$this->limit." offset ".$offset;exit;*/
                        
            $trRows=Yii::app()->db_gts->createCommand("select accountID,deviceID,devicePaymentStatus,isDamaged,vehicleModel,truckTypeId,vehicleType,licenseExpire,insuranceExpire,fitnessExpire,rcNo,NPAvailable,NPExpire,insuranceAmount,simPhoneNumber,simID,imeiNumber,lastValidLatitude,lastValidLongitude,lastValidSpeedKPH,lastGPSTimestamp,lastOdometerKM,isActive,creationTime,lookingForLoad,lookingForLoadDate from Device where installedById='".$uid."' ".$srchQry.$typeQry." order by creationTime desc limit ".$this->limit." offset ".$offset)->queryAll();
            if(count($trRows)){
                foreach($trRows as $trRow){
                    $json['data'][]=$trRow;
                }
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function actionupdateDeviceStatus() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/updateDeviceStatus
          method=post
          params=imei,type,uid,val
        */

        $json = array("status" => 0);
        $uid =(int)$_POST['uid'];
        $imei =$_POST['imei'];
        $type=$_POST['type']; //damaged,payment
        $val=$_POST['val'];//Pending,Collected,Deposited
        if(Yii::app()->request->isPostRequest) {
            if($type=='damaged'){
                $fieldData=array("isDamaged"=>$val);
            }else if($type=='payment'){
                $fieldData=array("devicePaymentStatus"=>$val);
            }
            GpsDevice::model()->updateAll($fieldData,"imeiNumber='".$imei."'");
            $json['status'] = 1;
	}
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
        public function actionactivateDevice() { 
        /*
          http://egcrm.cloudapp.net/operations/index.php/GPSapiV1/activateDevice
          method=post
          params=amount,vehicleModel,fname,lname,uid,imeiNumber,accountID,deviceID,truckTypeId,vehicleType,insuranceExpire,fitnessExpire,rcNo,NPAvailable,NPExpire,insuranceAmount,
         
         
         installedBy,devicePaymentStatus,installedById,vehicleID,uniqueID,isActive,displayName,description,creationTime, 
        */

        $json = array("status" => 0);
        $uid =(int)$_POST['uid'];
        $post=$_POST;
        $deviceID=strtoupper(trim($post['deviceID']));
		$accountAvail=Yii::app()->db_gts->createCommand("select count(*) from Account where accountID like '".$post['accountID']."'")->queryScalar();
        if(Yii::app()->request->isPostRequest && $post['accountID']!="" && $accountAvail && $deviceID!="" && $post['truckTypeId']!="" && $post['amount']!="") {
            
            $fieldData['accountID']=$post['accountID'];
            $fieldData['deviceID']=$deviceID;
            $fieldData['truckTypeId']=$post['truckTypeId'];
            $fieldData['vehicleType']=$post['vehicleType'];
            $fieldData['insuranceExpire']=$post['insuranceExpire'];
            $fieldData['fitnessExpire']=$post['fitnessExpire'];
            $fieldData['rcNo']=$post['rcNo'];
            $fieldData['NPAvailable']=$post['NPAvailable'];
            $fieldData['NPExpire']=$post['NPExpire'];
            $fieldData['insuranceAmount']=$post['insuranceAmount'];
            $fieldData['installedBy']=$post['fname']." ".$post['lname'];
            $fieldData['devicePaymentStatus']='Pending';
            $fieldData['installedById']=$uid;
            $fieldData['vehicleModel']=$post['vehicleModel'];
            $fieldData['vehicleID']=$deviceID;
            $fieldData['uniqueID']=$deviceID;
            $fieldData['isActive']=1;
            $fieldData['displayName']=$deviceID;
            $fieldData['description']=$deviceID;
            $fieldData['creationTime']=time();
			$fieldData['lastValidSpeedKPH']=0;
			$fieldData['lastDistanceKM']=0;
			$fieldData['lastOdometerKM']=0;

            $count=Yii::app()->db_gts->createCommand("select count(*) from Device where upper(deviceID) like '".$deviceID."'")->queryScalar();
            if(!$count){
            GpsDevice::model()->updateAll($fieldData,"imeiNumber='".$post['imeiNumber']."'");
            $planID=2;//Yearly Plan
            $planObj=GpsDevicePlans::model()->find('id_device_plans=2');
            $this->addGpsDevicePlan(array('deviceID'=>$deviceID,'accountID'=>$post['accountID'],'planID'=>$planID,'planName'=>$planObj->plan_name,'planAmount'=>$planObj->amount,'amount'=>$post['amount'],'duration'=>$planObj->duration_in_months,'startTime'=>date('Y-m-d')));    
            $json['status'] = 1;
            }else{
                $json['status'] = 0;
                $json['errors']="DeviceID should be unique!!";
            }
	}else{
            $json['errors']="AccountID not available,invalid details!!";
    }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function addGpsDevicePlan($data){
        $adph = new Accountdeviceplanhistory();
        $adph->deviceID = $data['deviceID'];
        $adph->accountID = $data['accountID'];
        $adph->planID = $data['planID'];
        $adph->planName = $data['planName'];
        $adph->planAmount = $data['planAmount'];
        $adph->amount = (int)$data['amount'];
        $adph->duration = $data['duration'];
        $adph->startTime = $data['startTime'];
        $adph->creationTime = date('Y-m-d h:i');
        $adph->expiryTime = date('Y-m-d', strtotime("+".$data['duration']." months", strtotime($data['startTime'])));
        $adph->save(false);
    }

	public function actionGetNotifications() { 
		/*
		  http://egcrm.cloudapp.net/operations/index.php/dcapi/GetNotifications
		  method=post
		  params=uid,offset
		*/

		$json = array("status" => 0);
		//$accountid = $_REQUEST['accountid'];
		$uid =(int)$_POST['uid'];
		$offset = (int)$_POST['offset'];

		if (Yii::app()->request->isPostRequest && $uid) {
			$datefrom=date('Y-m-d',time()-(86400*4));
			$json['status'] = 1;

			$trRows=Yii::app()->db->createCommand("select message,date_created from eg_notifications_truck_app where  date(date_created)>'".$datefrom."' order by date_created desc limit ".$this->limit." offset ".$offset)->queryAll();
			$total=Yii::app()->db->createCommand("select count(*) from eg_notifications_truck_app where date(date_created)>'".$datefrom."'")->queryScalar();
			$json['count']=$total;
			if(count($trRows)){
				foreach($trRows as $trRow){
				$json['data'][]=$trRow;
				}
			}else{
				$json['data']=array();
				}
		}
		echo CJSON::encode($json);
		Yii::app()->end();
	}

public function actionGetGpsLeads() { 
	/*
	  http://egcrm.cloudapp.net/operations/index.php/dcapi/GetGpsLeads
	  method=post
	  params=uid,offset
	*/
	$this->limit="20";
	$json = array("status" => 0);
	//$accountid = $_REQUEST['accountid'];
	$uid =(int)$_POST['uid'];
	$offset = (int)$_POST['offset'];

	if (Yii::app()->request->isPostRequest && $uid) {
		$json['status'] = 1;
		$trRows=Yii::app()->db->createCommand("select (CASE type
	WHEN 'T' THEN 'Truck'
    WHEN 'C' THEN 'Commission'
	WHEN 'TR' THEN 'Transporter'
	WHEN 'G' THEN 'Guest'
	WHEN 'L' THEN 'Load'
END) AS customer_type,fullname,mobile,address,city,state from eg_customer where gps_required=1 order by date_created desc limit ".$this->limit." offset ".$offset)->queryAll();
		$total=Yii::app()->db->createCommand("select count(*) from eg_customer where gps_required=1")->queryScalar();
		$json['count']=$total;
		if(count($trRows)){
			foreach($trRows as $trRow){
			$json['data'][]=$trRow;
			}
		}else{
			$json['data']=array();
			}
	}
	echo CJSON::encode($json);
	Yii::app()->end();
}
    
}
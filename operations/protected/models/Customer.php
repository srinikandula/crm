<?php


class Customer extends CActiveRecord
{
	public $confirm;
	public $name_company;
    public $no_of_trucks;
	public $no_of_loads;
	public $truck_reg_no;
	public $lead_status;
	public $lead_source;
	public $date_created;
	public $idprefix;
	public $id_admin_created;
	public $id_admin_assigned;
	public $id_customer_access_permission;
    public $date_created_since;    
	public $tonnes;
	public $smartphone_available;

	public $franchise_fullname;
	public $franchise_account;
 
	public function tableName()
	{
		return '{{customer}}';
	}

	public function rules()
	{
		return array(
            array('fullname,mobile,type','required'),
			array('email', 'email'),
                    array('mobile','length','min'=>10,'max'=>11),
			array('mobile,email','uniquedata'),
                    array('landline','length','min'=>8,'max'=>12),
    		/*array('password', 'length', 'min'=>6),
			array('password', 'required','on'=>'insert'),
			array('password, confirm', 'length', 'min'=>6),
			array('password, confirm', 'required','on'=>'insert'),*/
			array('confirm', 'compare', 'compareAttribute'=>'password'),
			array('landline', 'numerical', 'integerOnly'=>true),
			array('landline', 'length', 'max'=>12),
			array('status, approved', 'numerical', 'integerOnly'=>true),
			array('status,approved','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),
			array('date_created','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'insert'),
			array('date_fuel_card_applied,franchise_fullname,franchise_account,card_customer_no,card_username,card_password,card_status,card_cashback_percent,applied_fuel_card,id_franchise,id_admin_created,load_required,gps_required,smartphone_available,truck_reg_no,tonnes,date_created,operating_source_city,profile_image,id_customer,company,address,city,state,enable_sms_email_ads,rating,fullname, email, password,status,id_default_source_city, approved,no_of_trucks,no_of_loads,lead_status,lead_source,alt_mobile_1,alt_mobile_2,alt_mobile_3,idprefix,payment_type,no_of_vechiles,no_of_vechiles,year_in_service,bank_name,bank_account_no,bank_ifsc_code,bank_branch,id_customer_access_permission,tds_declaration_doc,load_payment_advance_percent,load_payment_topay_percent,load_payment_pod_days,pincode', 'safe'),
		);
	}
	
	public function uniquedata($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			//$qryStr=Yii::app()->controller->id!='leads'?'type!="G"   and islead=0 ':'';
			
			if($this->id_customer){
			$mob=Customer::model()->find(array('select'=>'count(*) as id_customer','condition'=>'type!="G" and id_customer!="'.$this->id_customer.'" and islead=0 and mobile="'.$this->mobile.'"'));
				if($this->email!=""){
				$em=Customer::model()->find(array('select'=>'count(*) as id_customer','condition'=>'type!="G" and islead=0 and id_customer!="'.$this->id_customer.'" '.$qryStr.' and email="'.$this->email.'"'));
				}
			}else{
				$mob=Customer::model()->find(array('select'=>'count(*) as id_customer','condition'=>  ' mobile="'.$this->mobile.'"'));
				if($this->email!=""){
				$em=Customer::model()->find(array('select'=>'count(*) as id_customer','condition'=> ' email="'.$this->email.'"'));
				}
			}	

			if($mob->id_customer){ 
				$this->addError('mobile','mobile should be unique.');
			}

			if($em->id_customer  && $this->type!='T'){//truck owner doesnt have email id
				$this->addError('email','email should be unique.');
			}

			//exit($em->id_customer." ".$mob->id_customer);
		}
	}

	public function attributeLabels()
	{
		return array(
			'gender' => 'Gender',
			'fullname' => 'Full Name',
			'landline' => 'Office Number',
			'email' => 'Email',
			'password' => 'Password',
			'status' => 'Status',
			'approved' => 'Approved',
			'enable_sms_email_ads' => 'Enable Sms/Email Ads',
			'idprefix' => 'ID'
			
		);
	}

	public function searchCustomerTruck()
	{

		$criteria=new CDbCriteria;
        $criteria->select="(select group_concat(tr.truck_reg_no) from {{truck}} tr where tr.id_customer=t.id_customer) as truck_reg_no,t.*,cl.id_admin_created,(select count(*) from {{truck}} tk where tk.id_customer=t.id_customer) as no_of_trucks";
		$criteria->compare('idprefix',$this->idprefix,true);
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('company',$this->company,true);
        $criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('landline',$this->landline,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		//$criteria->compare('no_of_trucks',$this->no_of_trucks);
		$criteria->compare('gps_required',$this->gps_required);
		$criteria->compare('load_required',$this->load_required);
		$criteria->compare('status',$this->status);
		
		if($_SESSION['id_franchise']!=1){
			$criteria->compare('id_franchise',(int)$_SESSION['id_franchise']);
		}
		
		$criteria->compare('approved',$this->approved);
		$criteria->compare('cl.id_admin_created',$this->id_admin_created);
		$criteria->compare('date_created',$this->date_created,true);
        $criteria->compare('type','T');
		//$criteria->condition='type="T"';
		$criteria->compare('trash','0');
		//$criteria->compare('islead','0');
		$criteria->join="left join {{customer_lead}} cl on t.id_customer=cl.id_customer";
		if($this->truck_reg_no!="" && $this->tonnes==''){
			$criteria->condition="t.id_customer in (select tr.id_customer from {{truck}} tr where tr.truck_reg_no like '%".$this->truck_reg_no."%')";
		}

		if($this->tonnes){
			$criteria->condition="(t.id_customer in (select cvt.id_customer from {{customer_vechile_types}} cvt where tonnes='".$this->tonnes."') or t.id_customer in (select tr.id_customer from {{truck}} tr,{{truck_type}} tt where tr.id_truck_type=tt.id_truck_type and tt.tonnes='".$this->tonnes."'))";
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
                ),
				'sort' => array(
					'defaultOrder' => 'id_customer DESC',
					),
		));
	}

	public function searchTransporter()
	{

		$criteria=new CDbCriteria;
        $criteria->select="t.*,cl.id_admin_created,(select count(*) from {{truck}} tk where tk.id_customer=t.id_customer) as no_of_trucks";
		$criteria->compare('idprefix',$this->idprefix,true);
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('company',$this->company,true);
                $criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('landline',$this->landline,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		//$criteria->compare('no_of_trucks',$this->no_of_trucks);
		$criteria->compare('gps_required',$this->gps_required);
		$criteria->compare('status',$this->status);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('cl.id_admin_created',$this->id_admin_created);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('type','TR');
		$criteria->compare('trash','0');
		//$criteria->compare('islead','0');
		//$criteria->condition='type="T"';
		$criteria->join="left join {{customer_lead}} cl on t.id_customer=cl.id_customer";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
                ),
				'sort' => array(
					'defaultOrder' => 'id_customer DESC',
					),
		));
	}

	public function searchCustomerLead()
	{

		//if(Yii::app()->getController('Controller')->listAllPerm){ echo 'true';}else{ echo 'false';};exit;
		$criteria=new CDbCriteria;
        $criteria->select="(select group_concat(tr.truck_reg_no) from {{truck}} tr where tr.id_customer=t.id_customer) as truck_reg_no,DATEDIFF('".date('Y-m-d')."',t.date_created) as date_created_since,t.*,cl.lead_source,cl.lead_status,(select  group_concat(distinct id_admin) from {{customer_access_history}} cah where cah.id_customer=t.id_customer ) as id_customer_access_permission";
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('t.id_customer',$this->id_customer);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('company',$this->company,true);
        $criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('landline',$this->landline,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		//$criteria->compare('no_of_trucks',$this->no_of_trucks);
		$criteria->compare('status',$this->status);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('gps_required',$this->gps_required);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_modified',$this->date_modified,true);
		if($_SESSION['id_admin_role']!=1){
			$criteria->compare('cl.id_admin_created',yii::app()->user->id);
		}
		$criteria->compare('cl.lead_status',$this->lead_status,true);
		//$criteria->compare('islead','1');
		$criteria->compare('trash','0');
		$str=" and (t.islead=1 or t.type='G')";
		//$str="";
		if($this->truck_reg_no!=""){
			$strFltr=" and t.id_customer in (select tr.id_customer from {{truck}} tr where tr.truck_reg_no like '%".$this->truck_reg_no."%') ";
		}
		//exit("value of ".$this->truck_reg_no);
		
		if(!Yii::app()->getController('Controller')->listAllPerm){//exit('in');
			$criteria->join='inner join {{customer_lead}} cl on cl.id_customer=t.id_customer  and cl.lead_status!="Junk Lead" inner join {{customer_access_permission}} cap on cap.id_customer=t.id_customer and cap.id_admin="'.Yii::app()->user->id.'"'.$str;
		}else{
			if($this->id_customer_access_permission){
				$str=" inner join {{customer_access_permission}} cap on cap.id_customer=t.id_customer and cap.id_admin in(select id_admin from {{admin}} a where a.status=1 and a.last_name like  '%".$this->id_customer_access_permission."%' or a.first_name like '%".$this->id_customer_access_permission."%' )";
			}
			$criteria->join='inner join {{customer_lead}} cl on cl.id_customer=t.id_customer  and cl.lead_status!="Junk Lead" '.$str.$strFltr;
		}
		//$criteria->condition='(type="G" or islead=1)';
		if($_SESSION['id_franchise']!=1){
			$criteria->condition='(id_franchise='.(int)$_SESSION['id_franchise'].')';
		}
		//$cObj=new Controller;
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
                ),
				'sort' => array(
					'defaultOrder' => 'id_customer DESC',
					),
		));
	}

	public function searchCustomerJunkLead()
	{

		//if(Yii::app()->getController('Controller')->listAllPerm){ echo 'true';}else{ echo 'false';};exit;
		$criteria=new CDbCriteria;
        $criteria->select="(select group_concat(tr.truck_reg_no) from {{truck}} tr where tr.id_customer=t.id_customer) as truck_reg_no,DATEDIFF('".date('Y-m-d')."',t.date_created) as date_created_since,t.*,cl.lead_source,cl.lead_status,(select  group_concat(distinct id_admin) from {{customer_access_history}} cah where cah.id_customer=t.id_customer ) as id_customer_access_permission";
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('t.id_customer',$this->id_customer);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('company',$this->company,true);
        $criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('landline',$this->landline,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		//$criteria->compare('no_of_trucks',$this->no_of_trucks);
		$criteria->compare('status',$this->status);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('gps_required',$this->gps_required);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_modified',$this->date_modified,true);
		if($_SESSION['id_admin_role']!=1){
			$criteria->compare('cl.id_admin_created',yii::app()->user->id);
		}
		$criteria->compare('cl.lead_status',$this->lead_status,true);
		//$criteria->compare('islead','1');
		$criteria->compare('trash','0');
		$str=" and (t.islead=1 or t.type='G')";
		//$str="";
		if($this->truck_reg_no!=""){
			$strFltr=" and t.id_customer in (select tr.id_customer from {{truck}} tr where tr.truck_reg_no like '%".$this->truck_reg_no."%') ";
		}
		//exit("value of ".$this->truck_reg_no);
		
		if(!Yii::app()->getController('Controller')->listAllPerm){//exit('in');
			$criteria->join='inner join {{customer_lead}} cl on cl.id_customer=t.id_customer  and cl.lead_status="Junk Lead" inner join {{customer_access_permission}} cap on cap.id_customer=t.id_customer and cap.id_admin="'.Yii::app()->user->id.'"'.$str;
		}else{
			if($this->id_customer_access_permission){
				$str=" inner join {{customer_access_permission}} cap on cap.id_customer=t.id_customer and cap.id_admin in(select id_admin from {{admin}} a where a.status=1 and a.last_name like  '%".$this->id_customer_access_permission."%' or a.first_name like '%".$this->id_customer_access_permission."%' )";
			}
			$criteria->join='inner join {{customer_lead}} cl on cl.id_customer=t.id_customer  and cl.lead_status="Junk Lead" '.$str.$strFltr;
		}
		//$criteria->condition='(type="G" or islead=1)';
		//$cObj=new Controller;
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
                ),
				'sort' => array(
					'defaultOrder' => 'id_customer DESC',
					),
		));
	}

	public function searchCustomerLoad()
	{

		$criteria=new CDbCriteria;
        $criteria->select="t.*,(select count(*) from {{load_truck_request}} ltr where ltr.id_customer=t.id_customer) as no_of_loads";
		$criteria->compare('idprefix',$this->idprefix,true);
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('company',$this->company,true);
                $criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('landline',$this->landline,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		//$criteria->compare('no_of_trucks',$this->no_of_trucks);
		$criteria->compare('status',$this->status);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('type','L');
		$criteria->compare('trash','0');
		//$criteria->compare('islead','0');
		//$criteria->condition='type="T"';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
                ),
				'sort' => array(
					'defaultOrder' => 'id_customer DESC',
					),
		));
	}

	public function searchGuest()
	{

		$criteria=new CDbCriteria;
        $criteria->select="t.*,(select count(*) from {{truck}} tr where tr.id_customer=t.id_customer) as no_of_trucks,(select count(*) from {{load_truck_request}} ltr where ltr.id_customer=t.id_customer) as no_of_loads";
                $criteria->compare('idprefix',$this->idprefix,true);	
                $criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('company',$this->company,true);
                $criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('landline',$this->landline,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		//$criteria->compare('no_of_trucks',$this->no_of_trucks);
		$criteria->compare('status',$this->status);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('type','G');
		$criteria->compare('trash','0');
		$criteria->compare('islead','0');
		//$criteria->condition='type="T"';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
                ),
				'sort' => array(
					'defaultOrder' => 'id_customer DESC',
					),
		));
	}

	public function customerAssignment(){
		
		$criteria=new CDbCriteria;
        $criteria->select="t.*,cl.id_admin_created,cl.id_admin_assigned,cl.lead_status,cl.lead_source";
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('lead_status',$this->lead_status,true);
		$criteria->compare('trash','0');
		$criteria->compare('islead','1');
		$criteria->join="inner join {{customer_lead}} cl on t.id_customer=cl.id_customer";
		//$criteria->condition='type="T"';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
                ),
				'sort' => array(
					'defaultOrder' => 't.date_created DESC',
					),
		));
	}

	public function searchCommissionAgent()
	{

		$criteria=new CDbCriteria;
        $criteria->select="t.*,(select count(*) from {{truck}} tr where tr.id_customer=t.id_customer) as no_of_trucks,(select count(*) from {{load_truck_request}} ltr where ltr.id_customer=t.id_customer) as no_of_loads";
		$criteria->compare('idprefix',$this->idprefix,true);
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('company',$this->company,true);
 
		$criteria->compare('landline',$this->landline,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		//$criteria->compare('no_of_trucks',$this->no_of_trucks);
		$criteria->compare('status',$this->status);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('type','C');
		$criteria->compare('trash','0');
		$criteria->compare('islead','0');
		//$criteria->condition='type="T"';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
                ),
				'sort' => array(
					'defaultOrder' => 'id_customer DESC',
					),
		));
	}

	/*public function searchCustomerLoad()
	{
		$criteria=new CDbCriteria;
	
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('company',$this->company,true);
 
		$criteria->compare('landline',$this->landline,true);
		//$criteria->compare('id_customer_group',$this->id_customer_group);
		$criteria->compare('status',$this->status);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->condition='type="L"';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
                ),
				'sort' => array(
					'defaultOrder' => 'id_customer DESC',
					),
		));
	}*/ 
    
 	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getCustomerInfo($input)
    {
        $criteria=new CDbCriteria;
        
        switch ($input['data'])
        {
            case 'PendingApproval':
								if(Yii::app()->config->getData('CONFIG_STORE_APPROVE_NEW_CUSTOMER')){
									$criteria->select="count(approved) as approved";
									$criteria->condition='approved=0';
									$model=Customer::model()->find($criteria);
									$return=$model->approved;
								}
                            break;
                        
            case 'TotalCustomers':
                                $criteria->select="count(id_customer) as id_customer";
                                //$criteria->condition='approved=0';
                                $model=Customer::model()->find($criteria);
                                $return=$model->id_customer;
                            break;
                        
            case 'RegisteredToday':
                                $criteria->select="count(id_customer) as id_customer";
                                $criteria->condition='date(date_created)="'.date(Y)."-".date(m)."-".date(d).'"';
                                $model=Customer::model()->find($criteria);
                                $return=$model->id_customer;
                            break;
        }
        return $return;
    }
	
	public function validatePassword($password)
	{
		return CPasswordHelper::verifyPassword($password,$this->password);
	}


	public function hashPassword($password)
	{
		return CPasswordHelper::hashPassword($password);
	}
	
	public function getCustomerName($id)
	{
		$name=Yii::app()->db->createCommand("select concat(firstname,' ',lastname) as customer from {{customer}} where id_customer=".$id)->queryScalar();
		return $name;
	}

	public function getApprovedActiveCustomers(){
		//return Customer::model()->findAll('approved=1 and islead=0 and status=1 and trash=0');
		return Customer::model()->findAll('type!="G"');
	}


	public function searchTrucks($data){

		$source=Library::getGPDetails($data['source_address']);
                
		$destination=Library::getGPDetails($data['destination_address']);
		$source_city=$source['city']==""?$source['input']:$source['city'];
		$source_state=$source['state']==""?$source['input']:$source['state'];
		$source_address=$source['input'];

		$destination_city=$destination['city']==""?$destination['input']:$destination['city'];
		$destination_state=$destination['state']==""?$destination['input']:$destination['state'];
		$destination_address=$destination['input'];
		//$id_truck_type=$data['id_truck_type'];
                $id_truck_type="select id_truck_type from {{truck_type}} where (id_truck_type='".$data['id_truck_type']."'  or tonnes='".$data['tonnes']."')"; // or tonnes='".$data['tonnes']."'
                
		$id_goods_type=$data['id_goods_type'];
		$tracking=$data['tracking'];
                //echo '<pre>';print_r($source);print_r($destination);exit;
		//and t.id_truck_type='$id_truck_type'
		//exit($destination_city." ".$destination_state." ".$destination_address);
		
		 $places=array('ai','si','ei','wi','ni');
		 $zoneStates=array();
		 $zoneStates['si']=array('Andhra Pradesh','Karnataka','Telangana State','Tamil Nadu','Kerala');
		 $zoneStates['ei']=array('Sikkim','West Bengal','Bihar','Jharkhand','Odisha','Assam','Manipur','Meghalaya','Mizoram','Nagaland');
		 $zoneStates['wi']=array('Goa','Rajasthan','Gujarat','Maharashtra');
		 $zoneStates['ni']=array('Madhya Pradesh','Chhattisgarh','Arunachal Pradesh','Punjab','Jammu and Kashmir','Haryana','Himachal Pradesh','Uttar Pradesh','Uttarakhand','Delhi');
		
		 $zoneStates=Library::getStateZones();
		 $source_zone=$zoneStates[$source_address];
		 $destination_zone=$zoneStates[$destination_address];
		 
		 $source_srch="";
		 $source_srch_tlr="";
		 if($source_zone!=""){
			$source_srch=" cod.source_address like '".$source_zone."' or cod.source_address like 'ai' or ";
			$source_tlr=" tlr.source_address like '".$source_zone."' or tlr.source_address like 'ai' or ";
		 }else{
			$source_srch=" cod.source_address like 'ai' or ";
			$source_tlr=" tlr.source_address like 'ai' or ";
		 }

		 $destination_srch="";
		 $destination_srch_tlrd="";
		 if($destination_zone!=""){
			$destination_srch=" cod.destination_address like '".$destination_zone."' or cod.destination_address like 'ai' or ";
			$destination_srch_tlrd=" tlrd.destination_address like '".$destination_zone."' or tlrd.destination_address like 'ai' or ";
		 }else{
			$destination_srch=" cod.destination_address like 'ai' or ";
			$destination_srch_tlrd=" tlrd.destination_address like 'ai' or ";
		 }


		$goods_type_condition="";
		$goods_type_condition_tlr="";
		if($id_goods_type==1){
			$goods_type_condition=" trp.id_goods_type=".$id_goods_type."  and ";  //only goods type of all will be retrieved
			$goods_type_condition_tlr=" tlr.id_goods_type=".$id_goods_type."  and ";  //only goods type of all will be retrieved
		}else{
			$goods_type_condition=" trp.id_goods_type in (1,".$id_goods_type.")  and "; // goods type of all and specific will be rectrieved
			$goods_type_condition_tlr=" tlr.id_goods_type in (1,".$id_goods_type.")  and "; // goods type of all and specific will be rectrieved
		}

		$order_by="";
		if($tracking){
			$order_by=" order by case when t.tracking_available=1 then 1 when t.tracking_available=0 then 2 end";
		}else{
			$order_by=" order by case when t.tracking_available=0 then 1 when t.tracking_available=1 then 2 end";
		}
		$rows3=array();	
		$rows1=array();
		$rows2=array();
                
                //from truck load request
		$rows3=Yii::app()->db->createCommand("select distinct c.*,tlr.*,tlrd.* from {{truck_load_request}} tlr,{{customer}} c,{{truck_load_request_destinations}} tlrd where c.id_customer=tlr.id_customer and tlr.id_truck_load_request=tlrd.id_truck_load_request and date(tlr.date_available)>='".date('Y-m-d')."' and ".$goods_type_condition_tlr." tlr.id_truck_type in (".$id_truck_type.") and (".$source_tlr." tlr.source_city like '%$source_city%' or tlr.source_address like '%$source_address%' or tlr.source_state like '%$source_state%') and (".$destination_srch_tlrd." tlrd.destination_city like '%$destination_city%' or tlrd.destination_address like '%$destination_address%' or tlrd.destination_state like '%$destination_state%')")->QueryAll();
		//echo '<pre>';print_r($rows3);exit;
                
                //from registered trucks
		$rows1=Yii::app()->db->createCommand("select distinct trp.id_truck_route_price,c.*,t.vehicle_insurance_expiry_date
,t.fitness_certificate_expiry_date,t.truck_reg_no,t.description,t.make_month,t.make_year,t.id_truck_type,t.tracking_available,t.insurance_available,t.vehicle_insurance,t.fitness_certificate,t.vehicle_rc,trp.id_goods_type,trp.source_address,trp.destination_address,trp.source_city,trp.destination_city from {{customer}} c,{{truck}} t, {{truck_route_price}} trp where c.id_customer=t.id_customer and t.id_truck=trp.id_truck  and t.approved=1 and t.id_truck_type in (".$id_truck_type.") and  t.status=1 and trp.status=1 and ".$goods_type_condition." (trp.source_city like '%$source_city%' or trp.source_address like '%$source_address%' or trp.source_state like '%$source_state%') and (trp.destination_city like '%$destination_city%' or trp.destination_address like '%$destination_address%' or trp.destination_state like '%$destination_state%') ".$order_by)->QueryAll();

		/*$rows2=Yii::app()->db->createCommand("select cod.id_customer_operating_destinations,c.idprefix,c.type,c.fullname,c.profile_image,c.mobile,c.alt_mobile_1,c.alt_mobile_2,c.alt_mobile_3,c.email,c.year_in_service,c.no_of_vechiles,c.payment_type,c.company,c.address,c.city,c.operating_source_city,c.state,c.landline,cod.* from {{customer}} c,{{customer_operating_destinations}} cod where c.id_customer=cod.id_customer and c.islead=0 and c.approved=1 and c.status=1 and c.type in ('C','T') and c.trash=0 and (".$source_srch." cod.source_city like '%$source_city%' or cod.source_address like '%$source_address%' or cod.source_state like '%$source_state%') and (".$destination_srch." cod.destination_city like '%$destination_city%' or cod.destination_address like '%$destination_address%' or cod.destination_state like '%$destination_state%')  order by  case when c.type='T' then 1 when c.type='C' then 2 end")->QueryAll();*/
                
                //from truck owners 
                $rows2=Yii::app()->db->createCommand("select distinct cod.id_customer_operating_destinations,c.idprefix,c.type,c.fullname,c.profile_image,c.mobile,c.alt_mobile_1,c.alt_mobile_2,c.alt_mobile_3,c.email,c.year_in_service,c.no_of_vechiles,c.payment_type,c.company,c.address,c.city,c.operating_source_city,c.state,c.landline,cod.*,cvt.title as truck_type,cvt.id_truck_type,cvt.tonnes from {{customer}} c,{{customer_operating_destinations}} cod,{{customer_vechile_types}} cvt where c.id_customer=cod.id_customer and c.id_customer=cvt.id_customer and c.islead=0 and c.approved=1 and c.status=1 and cvt.id_truck_type in (".$id_truck_type.") and c.type in ('T') and c.trash=0 and (".$source_srch." cod.source_city like '%$source_city%' or cod.source_address like '%$source_address%' or cod.source_state like '%$source_state%') and (".$destination_srch." cod.destination_city like '%$destination_city%' or cod.destination_address like '%$destination_address%' or cod.destination_state like '%$destination_state%')")->QueryAll();
                
                //form commission agents
                $rows4=Yii::app()->db->createCommand("select distinct cod.id_customer_operating_destinations,c.idprefix,c.type,c.fullname,c.profile_image,c.mobile,c.alt_mobile_1,c.alt_mobile_2,c.alt_mobile_3,c.email,c.year_in_service,c.no_of_vechiles,c.payment_type,c.company,c.address,c.city,c.operating_source_city,c.state,c.landline,cod.* from {{customer}} c,{{customer_operating_destinations}} cod where c.id_customer=cod.id_customer and c.islead=0 and c.approved=1 and c.status=1 and c.type in ('C') and c.trash=0 and (".$source_srch." cod.source_city like '%$source_city%' or cod.source_address like '%$source_address%' or cod.source_state like '%$source_state%') and (".$destination_srch." cod.destination_city like '%$destination_city%' or cod.destination_address like '%$destination_address%' or cod.destination_state like '%$destination_state%')")->QueryAll();
		
		//$array_merge=array($rows1,$rows2);
		//$rows1=$rows2;
		//echo '<pre>';print_r($rows1);exit;
		return array($rows3,$rows1,$rows2,$rows4);
	}

	public function getCustomersByQuoteRank($input){
		/*select id_customer, case when avg(percent)<=5 then 5 when (avg(percent)>5 and avg(percent)<15) then 3 else 1 end rating,avg(percent)  as avg_percent from (select id_load_truck_request, id_customer,expected_price,quote,
case   
when expected_price>=quote then '0'
when expected_price<quote then (100-((expected_price/quote)*100))
end  as percent
from (select ltr.expected_price as org_expected_price,case	when ltr.expected_price=0 then (select avg(sltrq.quote) from eg_load_truck_request_quotes sltrq where sltrq.id_load_truck_request=ltr.id_load_truck_request)
	when ltr.expected_price>0 then ltr.expected_price end as expected_price,ltr.id_load_truck_request,ltr.id_customer,ltrq.quote from eg_load_truck_request ltr,eg_load_truck_request_quotes ltrq where ltr.id_load_truck_request=ltrq.id_load_truck_request and ltr.isactive=1) as tab) as tab_master group by id_customer*/
		$strCust=array();
		$strCustQry="";
		$field="fullname";
		if($input[$field]!=""){
			$strCust[]="c.".$field." like '%".$input[$field]."%'";
		}
		
		$field="idprefix";
		if($input[$field]!=""){
			$strCust[]="c.".$field." like '%".$input[$field]."%'";
		}
		
		$field="mobile";
		if($input[$field]!=""){
			$strCust[]="c.".$field." like '%".$input[$field]."%'";
		}

		$field="address";
		if($input[$field]!=""){
			$strCust[]="c.".$field." like '%".$input[$field]."%'";
		}
		
		if(sizeof($strCust)){
			$strCustQry=" and (".implode(" or ",$strCust).")";
		}
		

		$field="rating";
		$strMainqry="";
		if($input[$field]!=""){
			$strMainqry=" having ".$field." = '".$input[$field]."'";
		}
		
		/*$qry="select c.fullname,c.idprefix,c.mobile,c.address,tab_master.id_customer, case when avg(tab_master.percent)<=5 then 5 when (avg(tab_master.percent)>5 and avg(tab_master.percent)<15) then 3 else 1 end rating,avg(tab_master.percent)  as avg_percent from (select id_load_truck_request, id_customer,expected_price,quote,
		case   
		when expected_price>=quote then '0'
		when expected_price<quote then (100-((expected_price/quote)*100))
		end  as percent
		from (select ltr.expected_price as org_expected_price,case	when ltr.expected_price=0 then (select avg(sltrq.quote) from eg_load_truck_request_quotes sltrq where sltrq.id_load_truck_request=ltr.id_load_truck_request)
		when ltr.expected_price>0 then ltr.expected_price end as expected_price,ltr.id_load_truck_request,ltr.id_customer,ltrq.quote from eg_load_truck_request ltr,eg_load_truck_request_quotes ltrq where ltr.id_load_truck_request=ltrq.id_load_truck_request and ltr.isactive=1) as tab) as tab_master inner join {{customer}} c on c.id_customer=tab_master.id_customer group by id_customer";*/
		
		$qry="select fullname,idprefix,mobile,address,id_customer, case when avg(tab_master.percent)<=5 then 5 when (avg(tab_master.percent)>5 and avg(tab_master.percent)<15) then 3 else 1 end rating,avg(tab_master.percent)  as avg_percent from (select fullname,idprefix,mobile,address,id_load_truck_request, id_customer,expected_price,quote,
		case   
		when expected_price>=quote then '0'
		when expected_price<quote then (100-((expected_price/quote)*100))
		end  as percent
		from (select c.fullname,c.idprefix,c.mobile,c.address,ltr.expected_price as org_expected_price,case	when ltr.expected_price=0 then (select avg(sltrq.quote) from eg_load_truck_request_quotes sltrq where sltrq.id_load_truck_request=ltr.id_load_truck_request)
		when ltr.expected_price>0 then ltr.expected_price end as expected_price,ltr.id_load_truck_request,ltrq.id_customer,ltrq.quote from eg_customer c,eg_load_truck_request ltr,eg_load_truck_request_quotes ltrq where c.id_customer=ltrq.id_customer and ltr.id_load_truck_request=ltrq.id_load_truck_request and ltr.isactive=1 ".$strCustQry.") as tab) as tab_master  group by id_customer ".$strMainqry;

		$count=Yii::app()->db->createCommand("select count(distinct(ltrq.id_customer)) as count from  {{customer}} c,{{load_truck_request}} ltr,{{load_truck_request_quotes}} ltrq where c.id_customer=ltrq.id_customer and ltr.id_load_truck_request=ltrq.id_load_truck_request and ltr.isactive=1 ".$strCustQry)->queryScalar();
		
		//echo $count."value of ".Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN');
		//exit($count['count']." value of ");
		$sql=Yii::app()->db->createCommand($qry);
		
		$sqlDataProvider=new CSqlDataProvider($sql, array(
			'totalItemCount'=>$count,
			'sort'=>array(
				'attributes'=>array(
					 'rating', 'fullname',
				),
				'defaultOrder' => array(
                            'rating' => CSort::SORT_DESC, //default sort value
                        ),
			),
			'pagination'=>array(
				'pageSize'=>'50',
			),
		));
		
		return $sqlDataProvider;
	}

	public function getMatchingDeviceList($data){
                $src=Library::getGPDetails($data['source']);
                //$src['city'];$src['state'];$src['address'];
                $trimSourceAddress=  str_replace(', India', '', $src['address']);
                $dest=Library::getGPDetails($data['destination']);
                //$dest['city'];$dest['state'];$dest['address'];
                $trimDestinationAddress=  str_replace(', India', '', $dest['address']);
                $return=array();
                //gps
                $gpsDtRows=Yii::app()->db->createCommand('select c.mobile,c.gps_account_id,l.username,l.device_id from  eg_gps_login_device l,eg_customer c where ((c.gps_account_id like l.username) or (c.mobile like l.username)) and c.load_required=1')->queryAll();
                $gpsDiv=array();
                $custDiv=array();
				
                foreach($gpsDtRows as $gpsDtRow){
                    if($gpsDtRow['gps_account_id']!=""){
                        $gpsDiv[$gpsDtRow['username']]=$gpsDtRow['device_id'];
                    }else{
                        $custDiv[$gpsDtRow['mobile']]=$gpsDtRow['device_id'];
                    }
                }
                if(sizeof($gpsDiv)){
                    $arrGpsAccount=array_keys($gpsDiv);
                    $strGpsAccount=strtolower(implode('","',$arrGpsAccount));
                    $matGpsAccounts=Yii::app()->db_gts->createCommand("select distinct accountID from AccountOperatingDestinations where accountID in (\"".$strGpsAccount."\") and (source_address like '%".$trimSourceAddress."%' and destination_address like '%".$trimDestinationAddress."%') or (destination_address like '%".$trimSourceAddress."%' and source_address like '%".$trimDestinationAddress."%')")->queryAll();
                }
				//echo "select distinct accountID from AccountOperatingDestinations where accountID in (\"".$strGpsAccount."\") and (source_address like '%".$trimSourceAddress."%' and destination_address like '%".$trimDestinationAddress."%') or (destination_address like '%".$trimSourceAddress."%' and source_address like '%".$trimDestinationAddress."%')";
				
                
                foreach($matGpsAccounts as $matGpsAccount){
					if($gpsDiv[$matGpsAccount[accountID]]!=""){
						$return[$gpsDiv[$matGpsAccount[accountID]]]=$gpsDiv[$matGpsAccount[accountID]];
					}
                }
                

                //customers
                if(sizeof($custDiv)){
					$arrCustAccount=array_keys($custDiv);
                    $arrCustMobile=implode(",",$arrCustAccount);
					//exit("select c.mobile from eg_customer c,eg_customer_operating_destinations cod where (c.id_customer=cod.id_customer and c.mobile in (".$arrCustMobile.")) and ((cod.source_address like '%".$trimSourceAddress."%' and cod.destination_address like '%".$trimDestinationAddress."%') or (cod.destination_address like '%".$trimSourceAddress."%' and cod.source_address like '%".$trimDestinationAddress."%'))");

                    $matCustAccounts=Yii::app()->db->createCommand("select c.mobile from eg_customer c,eg_customer_operating_destinations cod where (c.id_customer=cod.id_customer and c.mobile in (".$arrCustMobile.")) and ((cod.source_address like '%".$trimSourceAddress."%' and cod.destination_address like '%".$trimDestinationAddress."%') or (cod.destination_address like '%".$trimSourceAddress."%' and cod.source_address like '%".$trimDestinationAddress."%'))")->queryAll();
                
                    foreach($matCustAccounts as $matCustAccount){
                        $return[$custDiv[$matCustAccount[mobile]]]=$custDiv[$matCustAccount[mobile]];
                    }

									echo '<pre>';
				//print_r($gpsDiv);
				//print_r($custDiv);
				//print_r($matGpsAccounts);
				//print_r($matCustAccounts);
				//print_r($return);
				
				//exit;
                }
                return $return;
        }

	public function searchFuelAccount()
	{

		$criteria=new CDbCriteria;
        $criteria->select="t.*,f.fullname as franchise_fullname,f.account as franchise_account";
		$criteria->compare('idprefix',$this->idprefix,true);
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('company',$this->company,true);
 
		$criteria->compare('landline',$this->landline,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		//$criteria->compare('no_of_trucks',$this->no_of_trucks);
		$criteria->compare('status',$this->status);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_fuel_card_applied',$this->date_fuel_card_applied,true);
		
		//$criteria->compare('type','T');
		$criteria->compare('trash','0');
		$criteria->compare('t.id_franchise',$this->id_franchise);
		$criteria->compare('applied_fuel_card','1');
		$criteria->join='left join {{franchise}} f on t.id_franchise=f.id_franchise';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
                ),
				'sort' => array(
					'defaultOrder' => 'id_customer DESC',
					),
		));
	}
}
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
	public $idprefix;
        
	public function tableName()
	{
		return '{{customer}}';
	}

	public function rules()
	{
		return array(
            array('fullname,mobile,type','required'),
			array('email', 'email'),
			array('mobile,email','uniquedata'),
    		/*array('password', 'length', 'min'=>6),
			array('password', 'required','on'=>'insert'),
			array('password, confirm', 'length', 'min'=>6),
			array('password, confirm', 'required','on'=>'insert'),*/
			array('confirm', 'compare', 'compareAttribute'=>'password'),
			array('landline', 'numerical', 'integerOnly'=>true),
			array('landline', 'length', 'max'=>12),
			array('status, approved', 'numerical', 'integerOnly'=>true),
			array('status,approved','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),
			array('date_created,date_modified','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'insert'),
			array('operating_source_city,profile_image,id_customer,company,address,city,state,enable_sms_email_ads,rating,fullname, email, password,status,id_default_source_city, approved,no_of_trucks,no_of_loads,lead_status,lead_source,alt_mobile_1,alt_mobile_2,alt_mobile_3,idprefix,payment_type,no_of_vechiles,no_of_vechiles,year_in_service', 'safe'),
		);
	}
	
	public function uniquedata($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			if($this->id_customer){
			$mob=Customer::model()->find(array('select'=>'count(*) as id_customer','condition'=>'type!="G" and id_customer!="'.$this->id_customer.'" and islead=0 and mobile="'.$this->mobile.'"'));

			$em=Customer::model()->find(array('select'=>'count(*) as id_customer','condition'=>'type!="G" and id_customer!="'.$this->id_customer.'" and islead=0 and email="'.$this->email.'"'));
			}else{
				$mob=Customer::model()->find(array('select'=>'count(*) as id_customer','condition'=>'type!="G"  and islead=0 and mobile="'.$this->mobile.'"'));

				$em=Customer::model()->find(array('select'=>'count(*) as id_customer','condition'=>'type!="G" and islead=0 and email="'.$this->email.'"'));
			}	

			if($mob->id_customer){
				$this->addError('mobile','mobile should be unique.');
			}

			if($em->id_customer){
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
        $criteria->select="t.*,(select count(*) from {{truck}} tk where tk.id_customer=t.id_customer) as no_of_trucks";
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
		$criteria->compare('type','T');
		//$criteria->condition='type="T"';
		$criteria->compare('trash','0');
		$criteria->compare('islead','0');

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
        $criteria->select="t.*,(select count(*) from {{truck}} tk where tk.id_customer=t.id_customer) as no_of_trucks";
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
		$criteria->compare('type','TR');
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

	public function searchCustomerLead()
	{
		$criteria=new CDbCriteria;
        $criteria->select="t.*,cl.lead_source,cl.lead_status";
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
		$criteria->compare('islead','1');
		$criteria->compare('trash','0');
		if(!Yii::app()->getController('Controller')->listAllPerm){//exit('in');
			$criteria->compare('cl.id_admin_assigned',Yii::app()->user->id);
		}

		$criteria->join='inner join {{customer_lead}} cl on cl.id_customer=t.id_customer';
		//$criteria->condition='type="T"';
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
 
		$criteria->compare('landline',$this->landline,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		//$criteria->compare('no_of_trucks',$this->no_of_trucks);
		$criteria->compare('status',$this->status);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('type','L');
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

	public function searchGuest()
	{

		$criteria=new CDbCriteria;
        $criteria->select="t.*,(select count(*) from {{truck}} tr where tr.id_customer=t.id_customer) as no_of_trucks,(select count(*) from {{load_truck_request}} ltr where ltr.id_customer=t.id_customer) as no_of_loads";
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
		return Customer::model()->findAll('approved=1 and islead=0 and status=1 and trash=0');
	}


	public function searchTrucks($data){

		$source=Library::getGPDetails($data['source_address']);
		$destination=Library::getGPDetails($data['destination_address']);
		$source_city=$source['city'];
		$source_state=$source['state'];
		$source_address=$source['input'];

		$destination_city=$destination['city'];
		$destination_state=$destination['state'];
		$destination_address=$destination['input'];
		$id_truck_type=$data['id_truck_type'];

		//and t.id_truck_type='$id_truck_type'
		//exit($destination_city." ".$destination_state." ".$destination_address);
		$rows1=array();
		$rows2=array();
		$rows1=Yii::app()->db->createCommand("select trp.id_truck_route_price,c.*,t.truck_reg_no,t.description,t.make_month,t.make_year,t.id_truck_type,t.tracking_available,t.insurance_available,t.driver_driving_licence,t.vehicle_insurance,t.fitness_certificate,t.vehicle_rc,trp.source_address,trp.destination_address,trp.source_city,trp.destination_city from {{customer}} c,{{truck}} t, {{truck_route_price}} trp where c.id_customer=t.id_customer and t.id_truck=trp.id_truck  and t.approved=1 and t.id_truck_type='".$id_truck_type."' and  t.status=1 and trp.status=1  and (trp.source_city like '%$source_city%' or trp.source_address like '%$source_address%' or trp.source_state like '%$source_state%') and (trp.destination_city like '%$destination_city%' or trp.destination_address like '%$destination_address%' or trp.destination_state like '%$destination_state%') order by t.tracking_available desc")->QueryAll();

		$rows2=Yii::app()->db->createCommand("select cod.id_customer_operating_destinations,c.idprefix,c.type,c.fullname,c.profile_image,c.mobile,c.alt_mobile_1,c.alt_mobile_2,c.alt_mobile_3,c.email,c.year_in_service,c.no_of_vechiles,c.payment_type,c.company,c.address,c.city,c.operating_source_city,c.state,c.landline,cod.* from {{customer}} c,{{customer_operating_destinations}} cod where c.id_customer=cod.id_customer and c.islead=0 and c.approved=1 and c.status=1 and c.type in ('C','T') and c.trash=0 and (cod.source_city like '%$source_city%' or cod.source_address like '%$source_address%' or cod.source_state like '%$source_state%') and (cod.destination_city like '%$destination_city%' or cod.destination_address like '%$destination_address%' or cod.destination_state like '%$destination_state%')")->QueryAll();
		
		//$array_merge=array($rows1,$rows2);
		//$rows1=$rows2;
		//echo '<pre>';print_r($rows1);exit;
		return array($rows1,$rows2);
	}
}
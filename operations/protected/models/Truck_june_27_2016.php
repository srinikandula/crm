<?php

class Truck extends CActiveRecord
{
	public $fullname;
	public $idprefix;
	public $title;
        public $type;
	public $front_pic;
	public $right_pic;
	public $back_pic;
	public $left_pic;
	public $top_pic;
        public $date_start;
        public $date_end;
        public function tableName()
	{
		return '{{truck}}';
	}

	public function rules()
	{
		return array(
			array('id_customer,truck_reg_no,id_truck_type,tracking_available,insurance_available,status,make_year,make_month', 'required'),
			array('status,approved', 'numerical', 'integerOnly'=>true),
			array('id_customer_truck_attachment_policy,id_truck_attachment_policy,idprefix,description,source_address,source_city,source_state,source_lat,source_lng,fullname,title,front_pic,back_pic,left_pic,right,top_pic,vehicle_insurance,fitness_certificate,vehicle_rc,fitness_certificate_expiry_date,vehicle_insurance_expiry_date,gps_imei_no,gps_mobile_no,mileage,engine_no,chasis_no,rc_no,national_permit_available,national_permit_expiry_date,insurance_amount','safe'),	
			array('status','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array(
			'description'=>'Vehicle Specifications',
			'id_customer'=>'Customer',
			'truck_reg_no' => 'Truck Registration No',
			'id_truck_type' => 'Truck Type',
			'tracking_available'=>'Tracking Available',
		     
			);
	}


        
    public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->select="t.fitness_certificate_expiry_date,t.vehicle_insurance_expiry_date,t.vehicle_insurance,t.fitness_certificate,t.vehicle_rc,c.fullname,c.idprefix,c.type,t.id_truck,t.id_customer,t.truck_reg_no,t.tracking_available,t.status,t.approved,tt.title,t.mileage,t.id_truck_attachment_policy,ctap.date_start,ctap.date_end";
		$criteria->compare('t.truck_reg_no',$this->truck_reg_no,true);
		$criteria->compare('c.idprefix',$this->idprefix,true);
		$criteria->compare('c.fullname',$this->fullname,true);
		$criteria->compare('tt.title',$this->title,true);
                $criteria->compare('ctap.date_start',$this->date_start,true);
                $criteria->compare('ctap.date_end',$this->date_end,true);
                $criteria->compare('t.mileage',$this->mileage,true);
                $criteria->compare('t.id_truck_attachment_policy',$this->id_truck_attachment_policy,true);
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		$criteria->compare('t.tracking_available',$this->tracking_available);
		if((int)$_GET['cid']){ //if navigated from customer
			//$criteria->condition='t.id_customer="'.$_GET['cid'].'"';
			$criteria->compare('t.id_customer',$_GET['cid']);
		}
		
		if($_SESSION['id_admin_role']==8){ //for transporter
			//$criteria->condition='t.id_customer="'.(int)Yii::app()->user->id.'"';
			$criteria->compare('t.id_customer',(int)Yii::app()->user->id);
		}

		$criteria->join="left join {{customer}} c on c.id_customer=t.id_customer left join {{truck_type}} tt on t.id_truck_type=tt.id_truck_type left join {{customer_truck_attachment_policy}} ctap on t.id_customer_truck_attachment_policy=ctap.id_customer_truck_attachment_policy";
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_truck DESC',
				),
		));
	}
        
        

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
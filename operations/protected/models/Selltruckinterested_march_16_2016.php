<?php

class Selltruckinterested extends CActiveRecord
{

    public function tableName()
	{
		return '{{sell_truck_interested}}';
	}

	public function rules()
	{
		return array(
			array('	id_sell_truck', 'required'),
			array('status,date_created,id_customer,expected_price,gps_account_id', 'safe'),
			/*array('id_customer_truck_attachment_policy,id_truck_attachment_policy,idprefix,description,source_address,source_city,source_state,source_lat,source_lng,fullname,title,front_pic,back_pic,left_pic,right,top_pic,vehicle_insurance,fitness_certificate,vehicle_rc,fitness_certificate_expiry_date,vehicle_insurance_expiry_date,gps_imei_no,gps_mobile_no,mileage,engine_no,chasis_no','safe'),	
			array('status','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),*/
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array();
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
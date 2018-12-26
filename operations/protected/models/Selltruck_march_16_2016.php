<?php

class Selltruck extends CActiveRecord
{

    public function tableName()
	{
		return '{{sell_truck}}';
	}

	public function rules()
	{
		return array(
			array('truck_reg_no,contact_name,contact_mobile','required'),
			array('id_customer,gps_account_id,id_truck_type,truck_type_title,truck_reg_state,insurance_exp_date,fitness_exp_date,year_of_mfg,odometer,any_accidents,in_finance,expected_price,isactive,status,date_created,date_modifie,truck_front_pic,truck_back_pic,tyres_front_left_pic,tyres_front_right_pic,tyres_back_left_pic,tyres_back_right_pic,other_pic_1,other_pic_1','safe')
			/*array('id_customer,id_truck,truck_reg_no,id_truck_type,tracking_available,insurance_available,status,make_year,make_month,id_truck_attachment_policy', 'required'),
			array('status,approved', 'numerical', 'integerOnly'=>true),
			array('id_customer_truck_attachment_policy,id_truck_attachment_policy,idprefix,description,source_address,source_city,source_state,source_lat,source_lng,fullname,title,front_pic,back_pic,left_pic,right,top_pic,vehicle_insurance,fitness_certificate,vehicle_rc,fitness_certificate_expiry_date,vehicle_insurance_expiry_date,gps_imei_no,gps_mobile_no,mileage,engine_no,chasis_no','safe'),	
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
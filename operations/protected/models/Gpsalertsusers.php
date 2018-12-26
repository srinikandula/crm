<?php

class Gpsalertsusers extends CActiveRecord
{
	
        public function tableName()
	{
		return '{{gps_alerts_users}}';
	}

	public function rules()
	{
		return array(
			array('id_gps_alerts,gps_account_id', 'required'),
			array('id_customer_mobile', 'safe'),
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array("id_customer_mobile"=>"Customer");
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	

		
}

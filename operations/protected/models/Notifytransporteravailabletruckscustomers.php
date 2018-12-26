<?php

class Notifytransporteravailabletruckscustomers extends CActiveRecord
{
	
	public function tableName()
	{
		return '{{notify_transporter_available_trucks_customers}}';
	}

	public function rules()
	{
		return array(
			array('id_truck_load_request,id_customer', 'required'),
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array(
			'title'=>'Customer',
			'tracking'=>'Tracking Required',
			'insurance'=>'Insurance Required',
			'id_load_type'=>'Load Type',
			'id_truck_type'=>'Truck Type',
			'id_goods_type'=>'Goods Type',
			);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
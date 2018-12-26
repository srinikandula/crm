<?php

class Notifytransporteravailabletrucks extends CActiveRecord
{
	
	public function tableName()
	{
		return '{{notify_transporter_available_trucks}}';
	}

	public function rules()
	{
		return array(
			array('source_address,source_city,source_state,destination_city,destination_state,destination_address,id_truck_type,no_of_trucks,date_available,price', 'required'),
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
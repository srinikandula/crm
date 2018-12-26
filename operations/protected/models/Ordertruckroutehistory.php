<?php

class Ordertruckroutehistory extends CActiveRecord
{
    
 	public function tableName()
	{
		return '{{order_truck_route_history}}';
	}

	public function rules()
	{
		return array(
			array('address_location', 'required'),
			array('id_order,id_order_truck_route_history,time_date','safe'),
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
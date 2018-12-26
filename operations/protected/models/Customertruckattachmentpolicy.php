<?php

class Customertruckattachmentpolicy extends CActiveRecord
{
	public $title;
 	public function tableName()
	{
		return '{{customer_truck_attachment_policy}}';
	}

	public function rules()
	{
		return array(
			array('id_truck,id_truck_attachment_policy,min_kms,price_per_km,flat_rate,diesel_price_per_km,date_start,date_end', 'required'),
			//array('comment','safe'),
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
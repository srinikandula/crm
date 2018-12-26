<?php

class Truckdoc extends CActiveRecord
{
	public function tableName()
	{
		return '{{truck_doc}}';
	}

	public function rules()
	{
		return array(
			array('id_truck,file', 'required'),
			);
	}

	public function relations()
	{
		return array();
	}
	
    public function attributeLabels()
	{
		return array(
			'id_truck' => 'Truck',
			'file'=>'File',
			);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
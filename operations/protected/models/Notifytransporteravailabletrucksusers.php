<?php

class Notifytransporteravailabletrucksusers extends CActiveRecord
{
	public $total;
	public function tableName()
	{
		return '{{notify_transporter_available_trucks_users}}';
	}

	public function rules()
	{
		return array(
			array('id_customer,id_notify_transporter_available_trucks', 'required'),
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array(
			);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
<?php

class Gpsalertsinterested extends CActiveRecord
{
	public function tableName()
	{
		return '{{gps_alerts_interested}}';
	}

	public function rules()
	{
		return array(
			array('id_gps_alert,account_id', 'required'),
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
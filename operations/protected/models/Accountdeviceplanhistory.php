<?php

class Accountdeviceplanhistory extends GpsActiveRecord
{   
    public $duration;
    public $update_on;
    public function tableName()
	{
		return 'Accountdeviceplanhistory';
	}

	public function rules()
	{
		return array(
			array('planName', 'required'),
			array('remark,planID,planAmount,amount,expiryTime,startTime','safe'),
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
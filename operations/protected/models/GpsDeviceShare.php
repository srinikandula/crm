<?php

class GpsDeviceShare extends GpsActiveRecord
{
    
    public $msg;
	public function tableName()
	{
		return 'DeviceShare';
	}

	public function rules()
	{
		return array(
			array('accountID,deviceID,source,destination,link', 'required'),
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
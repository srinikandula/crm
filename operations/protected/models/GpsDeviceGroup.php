<?php

class GpsDeviceGroup extends GpsActiveRecord
{
    
    public function tableName()
	{
		return 'DeviceGroup';
	}

	public function rules()
	{
		return array(
			array('accountID', 'required'),
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
<?php

class GpsDeviceTyres extends GpsActiveRecord
{
    
    public function tableName()
	{
		return 'DeviceTyres';
	}

	public function rules()
	{
		return array(
			array('accountID,deviceID,level,position,expiryDate,installDate,km', 'required'),
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
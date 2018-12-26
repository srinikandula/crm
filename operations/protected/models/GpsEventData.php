<?php

class GpsEventData extends GpsActiveRecord
{
    
    public function tableName()
	{
		return 'EventData';
	}

	public function rules()
	{
		return array(
			array('accountID,deviceID', 'required'),
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
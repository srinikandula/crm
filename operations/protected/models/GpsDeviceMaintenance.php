<?php

class GpsDeviceMaintenance extends GpsActiveRecord
{
    
    public function tableName()
	{
		return 'DeviceMaintenance';
	}

	public function rules()
	{
		return array(
			array('accountID,deviceID', 'required'),
			array('nextGreasingDate,lastGreasingDate,greasingKm,nextServicingDate,lastServicingDate,servicingKm,eOilExpiryDate,eOilReplacedDate,eOilKm,driverName,driverMobile,nPDoc,nPExpire,insuranceDoc,insuranceExpire,fitnessDoc,fitnessExpire,rcExpire,rcDoc,pollDoc,pollExpire,driverLicenceNo,driverOnDuty', 'safe'),
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
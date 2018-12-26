<?php

class GpsTrip extends GpsActiveRecord
{
	public function tableName()
	{
		return 'Trip';
	}

	public function rules()
	{
		return array(
			array('accountID,deviceID,source,destination,startPointLat,startPointLng', 'required'),
			array('deviceID','match','pattern' => '/^\S*$/','message' => 'DeviceID should not contain spaces.'),
			//array('deviceID', 'length', 'min'=>5),
			array('destLat,destLng,endPointLat,endPointLng,startPointTime,endPointTime,odo,hoursTravelled,avgSpeed,dateTimeCreated', 'safe'),
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
        
    public function search()
	{
			
		//echo $criteria;exit;
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'dateTimeCreated DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
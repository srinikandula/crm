<?php

class GpsDevice extends GpsActiveRecord
{
	public function tableName()
	{
		return 'Device';
	}

	public function rules()
	{
		return array(
			array('accountID,deviceID,imeiNumber,simPhoneNumber,simID,truckTypeId', 'required'),
                        array('vehicleModel,vehicleMake,vehicleType,description,vehicleID,uniqueID,supportedEncodings,statusCodeState,isActive,creationTime', 'safe'),
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

		$criteria=new CDbCriteria;
                //$criteria->select="deviceID,accountID";
		$criteria->compare('deviceID',$this->deviceID,true);
                $criteria->compare('accountID',$this->accountID);
                $criteria->compare('simPhoneNumber',$this->simPhoneNumber);
                $criteria->compare('simID',$this->simID);
                $criteria->compare('imeiNumber',$this->imeiNumber);
                //echo $criteria;exit;
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'creationTime DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
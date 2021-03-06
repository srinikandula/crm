<?php

class GpsDevice extends GpsActiveRecord
{
	public $update_on;
	public $expiryTime;
	public function tableName()
	{
		return 'Device';
	}

	public function rules()
	{
		return array(
			array('accountID,deviceID,imeiNumber,simPhoneNumber,simID,truckTypeId,vehicleType,installedBy', 'required'),
			array('deviceID','match','pattern' => '/^\S*$/','message' => 'DeviceID should not contain spaces.'),
			array('deviceID', 'length', 'min'=>5),
			array('deviceID,imeiNumber,simID,simPhoneNumber','unique'),
            array('devicePaymentStatus,isDamaged,installedById,lookingForLoadDate,update_on,vehicleModel,vehicleMake,vehicleType,description,vehicleID,uniqueID,supportedEncodings,statusCodeState,isActive,creationTime,expiryTime,fitnessExpire,insuranceExpire,rcNo,NPAvailable,NPExpire,insuranceAmount', 'safe'),
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
		$criteria->select="t.*,(select concat(adph.expiryTime,'#',adph.received,'#',id) from Accountdeviceplanhistory adph where lower(adph.deviceID)=lower(t.deviceID) order by creationTime desc limit 1) as expiryTime";
		$criteria->compare('deviceID',$this->deviceID,true);
        $criteria->compare('accountID',$this->accountID,true);
        $criteria->compare('simPhoneNumber',$this->simPhoneNumber,true);
        $criteria->compare('simID',$this->simID,true);
        $criteria->compare('imeiNumber',$this->imeiNumber,true);
		$criteria->compare('vehicleType',$this->vehicleType);
		$criteria->compare('vehicleModel',$this->vehicleModel,true);
		$criteria->compare('isActive',$this->isActive);
		$criteria->compare('installedBy',$this->installedBy,true);
		$criteria->compare('creationTime',$this->creationTime,true);
		if($this->expiryTime!=""){
				$criteria->addCondition('deviceID in (select distinct deviceID from Accountdeviceplanhistory where  expiryTime like "%'.$this->expiryTime.'%")'); 
		}
        
		/*$rows=Yii::app()->db_gts->createCommand("select a.deviceID from Accountdeviceplanhistory a group by a.deviceID order by max(a.expiryTime) asc")->queryAll();
		$orderField="";
		foreach($rows as $row){
			$orderField.=$pre.'"'.$row['deviceID'].'"';
			$pre=",";
		}
		$criteria->order="FIELD(deviceID,".$orderField.")";*/
		
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
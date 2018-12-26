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
			array('simID','length','min'=>20,'max'=>20),
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
		//echo date('d-M H:i',time());
		//exit;
		$damaged=(int)$_GET['damaged'];	
		$notworking=(int)$_GET['notworking'];	
		$criteria=new CDbCriteria;
        //$criteria->select="deviceID,accountID";
		$criteria->select="t.*,(select concat(adph.expiryTime,'#',adph.received,'#',id) from Accountdeviceplanhistory adph where lower(adph.deviceID)=lower(t.deviceID) order by creationTime desc limit 1) as expiryTime";
		$criteria->compare('deviceID',$this->deviceID,true);
		
       
        
		$criteria->compare('simPhoneNumber',$this->simPhoneNumber,true);
        $criteria->compare('simID',$this->simID,true);
        $criteria->compare('imeiNumber',$this->imeiNumber,true);
		$criteria->compare('vehicleType',$this->vehicleType);
		$criteria->compare('vehicleModel',$this->vehicleModel,true);
		$criteria->compare('isActive',$this->isActive);
		$criteria->compare('lastGPSTimestamp',$this->lastGPSTimestamp);
		$criteria->compare('installedBy',$this->installedBy,true);
		$criteria->compare('devicePaymentStatus',$this->devicePaymentStatus,true);
		$criteria->compare('FROM_UNIXTIME(ifnull(creationTime,0), "%d-%b-%y")',$this->creationTime,true); //13-Jun-16
		//$criteria->compare('devicePaymentStatus'," ");
		//exit("value of ".$this->creationTime);
		if($_GET['GpsDevice']['combo']){ //from gpsdevicemgmt
			//exit("in if");
			$criteria->compare('accountID','santosh',true,"OR");
			$criteria->compare('accountID','accounts',true,"OR");
		}else{
			//exit("in else");
			$criteria->compare('accountID',$this->accountID,true);
		}

		if($this->installedById){
			$criteria->compare('installedById',$this->installedById);
			
			//exit("here");
		}
		//$criteria->compare('creationTime',$this->creationTime,true);
		if($damaged){
			$criteria->compare('isDamaged',1);
		}
		
		if($notworking){
			//$criteria->compare('lastGPSTimestamp',time()-41400,false,'<');//6 hours,5.30 hrs gmt time not added so removing that as well
			$dt=time()-41400;
			$criteria->addCondition('accountID!="santosh" and accountID!="accounts" and lastGPSTimestamp <'.$dt);
		}

		if($this->expiryTime!=""){
				//$criteria->addCondition('deviceID in (select distinct deviceID from Accountdeviceplanhistory where  expiryTime like "%'.$this->expiryTime.'%")');
				
			$criteria->addCondition('accountID in (select accountID from Account where retainedEventAge="'.$_SESSION['id_franchise'].'" ) and  deviceID in (select distinct deviceID from Accountdeviceplanhistory where  expiryTime like "%'.$this->expiryTime.'%")');
		}
		
		if($_SESSION['id_franchise']!=1) {
			$ids=Yii::app()->db->createCommand("select group_concat(id_admin) as id_admin from {{admin}} where id_franchise='".$_SESSION['id_franchise']."'")->queryRow();
		
			$criteria->addCondition('installedById in ('.$ids['id_admin'].')');
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
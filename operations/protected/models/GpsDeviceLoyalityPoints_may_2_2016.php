<?php

class GpsDeviceLoyalityPoints extends GpsActiveRecord
{
    
    public function tableName()
	{
		return 'DeviceLoyalityPoints';
	}

	public function rules()
	{
		return array(
			array('accountID,deviceID,activity,dateCreated,points', 'required'),
            array('status', 'safe'),
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
		//echo '<pre>';print_r($_GET);echo '</pre>';
		$criteria=new CDbCriteria;
        $criteria->select="t.*,lastGPSTimestamp+19800,(select concat(adph.expiryTime,'#',adph.received,'#',id) from Accountdeviceplanhistory adph where lower(adph.deviceID)=lower(t.deviceID) order by creationTime desc limit 1) as expiryTime";
		$criteria->compare('deviceID',$this->deviceID,true);
        $criteria->compare('accountID',$this->accountID,true);
        $criteria->compare('simPhoneNumber',$this->simPhoneNumber,true);
        $criteria->compare('simID',$this->simID,true);
		
        $criteria->compare('imeiNumber',$this->imeiNumber,true);
		$criteria->compare('vehicleType',$this->vehicleType,true);
		$criteria->compare('vehicleModel',$this->vehicleModel,true);
        $criteria->compare('creationTime',$this->creationTime,true);
		$criteria->compare('installedBy',$this->installedBy,true);
		$criteria->compare('isActive',$this->isActive,true);
        if($this->expiryTime!=""){
			
			$criteria->addCondition('deviceID in (select distinct deviceID from Accountdeviceplanhistory where  expiryTime like "%'.$this->expiryTime.'%")'); 
		}

		/*if($_GET['GpsDevice_sort']=='expiryTime.desc'){
			$criteria->order="FIELD('deviceID',select a.deviceID from Accountdeviceplanhistory a group by a.deviceID order by max(a.expiryTime) desc)";

		}else if($_GET['GpsDevice_sort']=='expiryTime.asc'){
			$criteria->order="FIELD('deviceID',select a.deviceID from Accountdeviceplanhistory a group by a.deviceID order by max(a.expiryTime) asc)";
		}*/

		//$criteria->order="FIELD(deviceID,select a.deviceID from accountdeviceplanhistory a group by a.deviceID order by max(a.expiryTime) asc)";
		//$criteria->order="FIELD('deviceID','demo,demo2,device3,device4')";
		$rows=Yii::app()->db_gts->createCommand("select a.deviceID from accountdeviceplanhistory a group by a.deviceID order by max(a.expiryTime) asc")->queryAll();
		$orderField="";
		foreach($rows as $row){
			$orderField.=$pre.'"'.$row['deviceID'].'"';
			$pre=",";
		}
		$criteria->order="FIELD(deviceID,".$orderField.")";

				//echo $criteria;exit;
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>'4',//Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
		'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			/*'sort' => array(
                //'defaultOrder' => 'creationTime DESC',
				'defaultOrder'=>'FIELD("deviceID",select deviceID from Accountdeviceplanhistory group by deviceID order by max(expiryTime) desc)',
				),*/
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
<?php

class GpsAccount extends GpsActiveRecord
{
    public $no_of_vehicles;
	public $customer_type;
	public $operating_routes_count;
    public function tableName()
	{
		return 'Account';
	}

	public function rules()
	{
		return array(
			array('createdById,customer_type,accountID,contactName,contactPhone,password,vehicleType,isActive,smsEnabled', 'required'),
            array('stopAlertTime,privateLabelName,contactAddress,displayName,distanceUnits,temperatureUnits,vehicleType,contactEmail,smsEnabled,speedUnits','safe'),
                    array('accountID','match','pattern' => '/^\S*$/','message' => 'AccountID should not contain spaces.'),
            array('isActive', 'numerical', 'integerOnly'=>true),
			array('accountID', 'length', 'min'=>5),
			array('accountID','unique'),
			array('contactPhone','unique'),
			array('isActive','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),
                    array('speedUnits','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),
                    );
	}

	public function relations()
	{
		return array();
	}
	
    public function attributeLabels()
	{
		return array('privateLabelName'=>'Alt Phone');
	}
        public function search()
	{

		$criteria=new CDbCriteria;
                $criteria->select="accountID,contactEmail,contactPhone,password,vehicleType,smsEnabled,creationTime,isActive,t.*,(select count(o.accountID) as orc from AccountOperatingDestinations o where o.accountID=t.accountID) as operating_routes_count ,(select count(*) from Device d where t.accountID= d.accountID) as no_of_vehicles";
		$criteria->compare('accountID',$this->accountID,true);
                $criteria->compare('contactEmail',$this->contactEmail,true);
				$criteria->compare('contactName',$this->contactName,true);
				$criteria->compare('contactAddress',$this->contactAddress,true);
                $criteria->compare('contactPhone',$this->contactPhone,true);
                $criteria->compare('password',$this->password,true);
                $criteria->compare('vehicleType',$this->vehicleType,true);
                $criteria->compare('smsEnabled',$this->smsEnabled,true);
                $criteria->compare('creationTime',$this->creationTime,true);
                $criteria->compare('isActive',$this->isActive,true);
				$cond=$_SESSION['id_franchise']==1?'':'retainedEventAge="'.$_SESSION['id_franchise'].'" and ';
				$criteria->addCondition($cond.'(accountID!="demo" and accountID!="sysadmin")');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'creationTime desc',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
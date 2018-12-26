<?php

class GpsAccount extends GpsActiveRecord
{
	public function tableName()
	{
		return 'Account';
	}

	public function rules()
	{
		return array(
			array('accountID,contactName,contactPhone,password', 'required'),
                        array('vehicleType,contactEmail,smsEnabled,speedUnits','safe'),
                        array('isActive', 'numerical', 'integerOnly'=>true),
			array('accountID', 'length', 'min'=>5),
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
		return array();
	}
        public function search()
	{

		$criteria=new CDbCriteria;
		$criteria->compare('accountID',$this->accountID,true);
                $criteria->compare('contactEmail',$this->contactEmail);
                $criteria->compare('contactPhone',$this->contactPhone);
                $criteria->compare('vehicleType',$this->vehicleType);
                $criteria->compare('smsEnabled',$this->smsEnabled);
                $criteria->compare('isActive',$this->isActive);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'creationTime',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
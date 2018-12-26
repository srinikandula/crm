<?php

class GpsDeviceLoyalityPoints extends GpsActiveRecord
{
    public $contactPhone;
    public function tableName()
	{
		return 'DeviceLoyalityPoints';
	}

	public function rules()
	{
		return array(
			array('accountID,deviceID,activity,dateCreated,points', 'required'),
            array('contactPhone,status', 'safe'),
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
		$criteria->select="a.contactPhone,t.*";
        /*$criteria->compare('deviceID',$this->deviceID,true);
        $criteria->compare('accountID',$this->accountID,true);
        $criteria->compare('simPhoneNumber',$this->simPhoneNumber,true);
        $criteria->compare('simID',$this->simID,true);*/
		$criteria->join="Left join Account a on a.accountID=t.accountID";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>'4',//Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
		'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'dateCreated DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
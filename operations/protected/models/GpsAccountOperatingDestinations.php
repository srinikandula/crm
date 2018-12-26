<?php

class GpsAccountOperatingDestinations extends GpsActiveRecord
{
	public function tableName()
	{
		return 'AccountOperatingDestinations';
	}

	public function rules()
	{
		return array(
			array('accountID', 'required'),
			array('source_city,source_state,source_address,source_lat,source_lng,destination_city,destination_state,destination_address,destination_lat,destination_lng', 'safe'),
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
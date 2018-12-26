<?php

class GpsDevicePlans extends GpsActiveRecord
{
	
        public function tableName()
	{
		return 'DevicePlans';
	}

	public function rules()
	{
		return array(
			array('id_franchise,plan_name,amount,duration_in_months', 'required'),
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

		$criteria=new CDbCriteria;
		$criteria->compare('plan_name',$this->plan_name,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('id_franchise',(int)$_SESSION['id_franchise']);
        $criteria->compare('duration_in_months',$this->duration_in_months,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_device_plans DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	

		
}

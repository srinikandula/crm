<?php

class Customertrafficmanager extends CActiveRecord
{
	
        public function tableName()
	{
		return '{{customer_traffic_manager}}';
	}

	public function rules()
	{
		return array(
			array('full_name,mobile', 'required'),
			array('id_customer,city','safe'),
			
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array(
			'title' => 'Title',
			'tonnes' => 'Tonnes',
                        'mileage' => 'Mileage',
			'status' => 'Status',
			
			);
	}


        
    public function search()
	{

		$criteria=new CDbCriteria;
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.tonnes',$this->tonnes);
                $criteria->compare('t.mileage',$this->mileage);
		$criteria->compare('t.status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_truck_type DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

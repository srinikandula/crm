<?php

class Notificationstruckapp extends CActiveRecord
{
	
    public function tableName()
	{
		return '{{notifications_truck_app}}';
	}

	public function rules()
	{
		return array(
			array('message', 'required'),
			array('date_created','safe'),
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
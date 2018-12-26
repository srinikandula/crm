<?php

class Orderstatus extends CActiveRecord
{
 	public function tableName()
	{
		return '{{order_status}}';
	}

	public function rules()
	{
		return array(
			array('title,status,release_truck', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('status','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),
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
			'release_truck' => 'Release Truck',
			'status' => 'Status',
			);
	}


        
    public function search()
	{

		$criteria=new CDbCriteria;
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.release_truck',$this->release_truck);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_order_status DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

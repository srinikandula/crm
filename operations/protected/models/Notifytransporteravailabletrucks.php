<?php

class Notifytransporteravailabletrucks extends CActiveRecord
{
	public $total;
	public function tableName()
	{
		return '{{notify_transporter_available_trucks}}';
	}

	public function rules()
	{
		return array(
			array('source_city,destination_city,id_truck_type,no_of_trucks,date_available,price,sendtoall', 'required'),
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array(
			'title'=>'Customer',
			'tracking'=>'Tracking Required',
			'insurance'=>'Insurance Required',
			'id_load_type'=>'Load Type',
			'id_truck_type'=>'Truck Type',
			'id_goods_type'=>'Goods Type',
			);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function search()
	{
                $criteria=new CDbCriteria;
                $criteria->select="t.*,(select count(*) as total from eg_notify_transporter_available_trucks_customers gai where t.id_notify_transporter_available_trucks=gai.id_notify_transporter_available_trucks) as total";
				$criteria->compare('source_city',$this->source_city,true);
				$criteria->compare('destination_city',$this->destination_city,true);
				$criteria->compare('date_available',$this->date_available,true);
				$criteria->compare('date_created',$this->date_created,true);
				$criteria->compare('sendtoall',$this->sendtoall,true);
                return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_notify_transporter_available_trucks DESC',
				),
		));
	}
}
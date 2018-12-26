<?php

class Trucktype extends CActiveRecord
{
	public $zone_search;
        public $state;

        public function tableName()
	{
		return '{{truck_type}}';
	}

	public function rules()
	{
		return array(
			array('title,status,tonnes,mileage', 'required'),
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
	
	public function getTruckTypes(){
		$cid="truck_type";
		$cache = Yii::app()->cache;
		$data=$cache->get($cid);
        if($data===false){
			$data=array();
			foreach(Trucktype::model()->findAll() as $obj){
				$data[$obj->id_truck_type]=$obj->title;
			}
			$cache->set($cid, $data, 100, new CDbCacheDependency('select max(date_modified) as date_modified from {{truck_type}}'));
		}
		return $data;
	}

		
}


<?php

class Zone extends CActiveRecord
{
    
	public function tableName()
	{
		return '{{zone}}';
	}

	public function rules()
	{
		return array(
            array('name', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>300),
			array('date_modified','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false),
			array('status','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),
			array('id_zone, name, status', 'safe', 'on'=>'search'),
		);
	}


	public function attributeLabels()
	{
		return array(
		'name' => 'Name',
		'status' => 'Status',
		);
	}

	public function search()
	{
            $criteria=new CDbCriteria;
            $criteria->compare('name',$this->name,true);
            $criteria->compare('status',$this->status);

            return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'pagination'=>array(
                'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
				),
				'sort' => array(
                'defaultOrder' => 'id_zone DESC',
				),
            ));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function checkCountryExists($zone_id)
        {
            $countryList = country::model()->findByAttributes(array('id_zone'=>$zone_id));
            if(count($countryList)>0)
                return false;
            return true;
        }
        
        public function getZones()
        {
            $cache=Yii::app()->cache;
            $zones=$cache->get('a_zones');
            if($zones===false)
            {
                    $zones=Zone::model()->findAll();
                    $cache->set('a_zones',$zones , Yii::app()->config->getData('CONFIG_WEBSITE_CACHE_LIFE_TIME'), new CDbCacheDependency('SELECT concat(MAX(date_modified),"-",count(id_zone)) as date_modified FROM {{zone}}'));
            }
            return $zones;
        }

}

<?php

class State extends CActiveRecord
{
	public  $country_search;
	public function tableName()
	{
		return '{{state}}';
	}

	public function rules()
	{

		return array(
            array('name','required'),
			array('id_country, status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>64),
			array('code', 'length', 'max'=>32),
			array('status','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),
			array('date_modified','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false),
			array('id_state, name, code, id_country, status,country_search', 'safe', 'on'=>'search'),
		);
	}


	public function relations()
	{
		return array(
			'country'=>array(self::BELONGS_TO,'Country','id_country'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'name' => 'Name',
			'code' => 'Code',
			'id_country' => 'Country',
			'status' => 'Status'
			);
	}


	public function search()
	{
		$criteria=new CDbCriteria;
		//$criteria->compare('id_state',$this->id_state);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('code',$this->code,true);
		$criteria->with=array('country');
		$criteria->compare('country.name',$this->country_search,true);
		$criteria->compare('t.status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_state DESC',
			),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
    public function getStates($condition){
		return State::model()->findAll($condition);
		 /*$cache=Yii::app()->cache;
            $states=$cache->get('a_states_'.Yii::app()->language);
            if($states===false)
            {
                $states=State::model()->findAll();
                $cache->set('a_states_'.Yii::app()->language,$states , Yii::app()->config->getData('CONFIG_WEBSITE_CACHE_LIFE_TIME'), new CDbCacheDependency('SELECT concat(MAX(date_modified),"-",count(id_state)) as date_modified FROM {{state}}'));
            }
        return $states;*/
    }
}

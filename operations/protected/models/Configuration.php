<?php

class Configuration extends CActiveRecord
{
        public function tableName()
	{
		return '{{configuration}}';
	}

	public function rules()
	{

		return array(
			array('id_configuration_group, code,key,value', 'safe'),
		);
	}


        
    public function getConfigurations($condition)
    {
		/*echo '<pre>';print_r($condition);echo '</pre>';
		//exit($condition);
		$cache=Yii::app()->cache;
        $configurations=$cache->get('a_configurations');
        if($configurations===false)
        {
            $configurations=Configuration::model()->findAll($condition);
            $cache->set('a_configurations',$configurations , 0, new CDbCacheDependency('SELECT MAX(date_modified) as date_modified FROM {{configuration_group}}'));
        }
        return $configurations;*/

		return Configuration::model()->findAll($condition);
    }
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

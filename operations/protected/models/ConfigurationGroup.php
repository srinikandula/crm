<?php

class ConfigurationGroup extends CActiveRecord
{
	public function tableName()
	{
		return '{{configuration_group}}';
	}

	public function rules()
	{
		return array(
			array('date_modified','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false),
			array('type, code,date_created,date_modified', 'safe'),
		);
	}
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}


<?php

class Gpsalerts extends CActiveRecord
{
	public function tableName()
	{
		return '{{gps_alerts}}';
	}

	public function rules()
	{
		return array(
			array('message', 'required'),
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

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

class Gpslogindevice extends CActiveRecord
{
	
    public function tableName()
	{
		return '{{gps_login_device}}';
	}

	public function rules()
	{
		return array(
			array('username,device_id', 'required'),
			array('duplicate', 'safe'),
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

	public function updateDuplicates($json){
		$jArray=json_decode($json);
		foreach($jArray->results as $k=>$v){
			//echo isset($v->registration_id)?$v->registration_id."<br/>":"";
			if(isset($v->registration_id)){
				Yii::app()->db->createCommand("update {{gps_login_device}} set duplicate=1 where device_id='".$v->registration_id."'")->query();		
			}
		}
	}

	public function updateDuplicateDevices($devices,$json){
		$jArray=json_decode($json);
		foreach($jArray->results as $k=>$v){
			//echo isset($v->registration_id)?$v->registration_id."<br/>":"";
			if(isset($v->registration_id)){
				Yii::app()->db->createCommand("update {{gps_login_device}} set device_id='".$v->registration_id."' where device_id='".$devices[$k]."'")->query();		
			}
		}
	}
}
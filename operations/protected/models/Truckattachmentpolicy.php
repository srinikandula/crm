<?php

class Truckattachmentpolicy extends CActiveRecord
{
 	public function tableName()
	{
		return '{{truck_attachment_policy}}';
	}

	public function rules()
	{
		return array(
			array('title,description,status', 'required'),
			//array('comment','safe'),
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
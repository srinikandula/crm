<?php

class Customerpointsredeem extends CActiveRecord
{
 
	public function tableName()
	{
		return '{{customer_points_redeem}}';
	}

	public function rules()
	{
		return array(
            array('id_customer,id_loyality_gifts,received_gift','required'),
			array('date_created', 'safe'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'gender' => 'Gender',
			'fullname' => 'Full Name',
			'landline' => 'Office Number',
			'email' => 'Email',
			'password' => 'Password',
			'status' => 'Status',
			'approved' => 'Approved',
			'enable_sms_email_ads' => 'Enable Sms/Email Ads',
			'idprefix' => 'ID'
			
		);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
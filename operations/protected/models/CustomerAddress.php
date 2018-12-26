<?php

class CustomerAddress extends CActiveRecord
{
	public function tableName()
	{
		return '{{customer_address}}';
	}

	public function rules()
	{
		return array(
			array('id_customer, id_state, id_country', 'numerical', 'integerOnly'=>true),
			array('firstname, lastname, city', 'length', 'max'=>150),
			array('telephone, postcode', 'length', 'max'=>30),
			array('company', 'length', 'max'=>100),
			array('address_1, address_2', 'length', 'max'=>255),
			array('id_customer_address, id_customer, firstname, lastname, telephone, company, address_1, address_2, city, id_state, id_country, postcode', 'safe', 'on'=>'search'),
		);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

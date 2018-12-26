<?php

class Ordertransactionhistory extends CActiveRecord
{
 	public function tableName()
	{
		return '{{order_transaction_history}}';
	}

	public function rules()
	{
		return array(
			array('customer_type,id_customer,id_order,amount_prefix,amount', 'required'),
			array('comment,bank','safe'),
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
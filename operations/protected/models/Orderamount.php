<?php

class Orderamount extends CActiveRecord
{
 	public function tableName()
	{
		return '{{order_amount}}';
	}

	public function rules()
	{
		return array(
			array('amount_prefix,amount,id_order', 'required'),
			array('id_order_amount,comment','safe'),
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


        
    public function search()
	{

		$criteria=new CDbCriteria;
		/*$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.status',$this->status);*/

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_order_history DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
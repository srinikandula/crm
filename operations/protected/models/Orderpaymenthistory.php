<?php

class Orderpaymenthistory extends CActiveRecord
{
 	
	public $date_created;
	public function tableName()
	{
		return '{{order_payment_history}}';
	}

	public function rules()
	{
		return array(
			array('amount,id_order,id_customer', 'required'),
			array('comment,date_created','safe'),
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
                'defaultOrder' => 'id_order_payment_history DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
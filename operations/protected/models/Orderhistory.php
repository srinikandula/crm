<?php

class Orderhistory extends CActiveRecord
{
 	public function tableName()
	{
		return '{{order_history}}';
	}

	public function rules()
	{
		return array(
			array('id_order_status,id_order', 'required'),
			array('title,message,notified_by_customer','safe'),
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array('notified_by_customer'=>'Notify Customer');
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

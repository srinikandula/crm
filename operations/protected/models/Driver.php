<?php

class Driver extends CActiveRecord
{
	public function tableName()
	{
		return '{{driver}}';
	}

	public function rules()
	{
		return array(
			array('name,mobile,licence_pic,date_created', 'required'),
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
		$criteria->compare('t.city',$this->city,true);
		$criteria->compare('t.state',$this->state,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'source DESC,destination DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getCustomersDriver($id){
		$criteria=new CDbCriteria;
		$criteria->join='inner join {{customer_driver_current}} dr on t.id_driver=dr.id_driver and dr.id_customer="'.$id.'"';
		return Driver::model()->findAll($criteria);
	}
}
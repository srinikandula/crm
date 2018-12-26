<?php

class Franchise extends CActiveRecord
{
	public function tableName()
	{
		return '{{franchise}}';
	}

	public function rules()
	{
		return array(
			array('fullname,account,mobile,address', 'required'),
			array('account', 'unique'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('doj,date_created,service_tax_no,pancard,bank_details,company,state,city,landline','safe'),	
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
		$criteria->select="t.*";
		$criteria->compare('c.fullname',$this->fullname,true);
		$criteria->compare('t.account',$this->account,true);
		$criteria->compare('c.mobile',$this->mobile,true);
		$criteria->compare('t.status',$this->status,true);
		$criteria->compare('t.date_created',$this->date_created,true);
		$criteria->addCondition("id_franchise>1");
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_franchise DESC',
			),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
<?php

class Fuelcard extends CActiveRecord
{
	public function tableName()
	{
		return '{{fuel_card}}';
	}

	public function rules()
	{
		return array(
			array('id_fuel_card,card_no', 'required'),
			array('id_customer,vehicle_no,issue_date,expiry_date,status','safe'),	
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
		//$criteria->select="t.*";
		$criteria->compare('card_no',$this->card_no,true);
		$criteria->compare('vehicle_no',$this->vehicle_no,true);
		/*$criteria->compare('c.mobile',$this->mobile,true);
		$criteria->compare('t.status',$this->status,true);
		$criteria->compare('t.date_created',$this->date_created,true);
		$criteria->addCondition("id_franchise>1");*/
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
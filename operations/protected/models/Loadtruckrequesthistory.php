<?php

class Loadtruckrequesthistory extends CActiveRecord
{
	public function tableName()
	{
		return '{{load_truck_request_history}}';
	}

	public function rules()
	{
		return array(
			array('id_load_truck_request,source_address,destination_address,expected_price,expected_price_comment,date_required,id_goods_type,tracking,comment,pickup_point,id_truck_type,insurance,modified_fields' ,'required'),
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
}
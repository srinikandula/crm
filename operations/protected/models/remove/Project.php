<?php

class Project extends CActiveRecord
{
	public function tableName()
	{
		return '{{project}}';
	}

	public function rules()
	{

		return array(
            array('id_customer,part_name,part_number','required'),
			array('part_name,part_number,drawing,3d_model_photo','length', 'max'=>30),
			array('status','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),
			array('category,id_supplier,project_by_admin,project_status,manufacturing_status,target_price,lead_time,date_of_requirement,special_conditions,payment_terms,size,deadline', 'safe'),
		);
	}


	public function relations()
	{
		return array();
	}


	public function attributeLabels()
	{
		return array('id_supplier' => 'Assigned To Supplier',);
	}


	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('t.part_number',$this->part_number);
		$criteria->compare('t.part_name',$this->part_name,true);
		$criteria->compare('t.status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_project DESC',
			),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
}
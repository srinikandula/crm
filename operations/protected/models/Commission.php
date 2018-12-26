<?php

class Commission extends CActiveRecord
{
	public function tableName()
	{
		return '{{commission}}';
	}

	public function rules()
	{
		return array(
			array('type,commission,commission_type', 'required'),
			array('title,id,source_city,destination_city', 'safe'),
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
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			/*'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),*/
			'sort' => array(
                'defaultOrder' => 'id_commission DESC,type ASC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
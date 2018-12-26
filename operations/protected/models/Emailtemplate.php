<?php

class Emailtemplate extends CActiveRecord
{
	public $zone_search;

	public function tableName()
	{
		return '{{email_template}}';
	}

	public function rules()
	{
		return array(
			array('type', 'required'),
			array('html,keywords,title,subject,description,mobile_keywords,mobile_description,type','safe'),
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array(
			);
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
                'defaultOrder' => 'date_modified DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	} 
}
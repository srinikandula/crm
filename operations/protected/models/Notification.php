<?php

class Notification extends CActiveRecord
{
	public $type;
	public $fullname;
	
	public function tableName()
	{
		return '{{notification}}';
	}

	/*public function rules()
	{
		return array(
			array('id_customer,code,url,action', 'required'),
			array('verified', 'numerical', 'integerOnly'=>true),
			array('fullname,date_created,type','safe'),	
		);
	}*/

	public function rules()
	{
		return array(
			array('info,visibility', 'required'),
			array('accountID,id_customer,info,dateCreated','safe'),	
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
		$criteria->select="t.*,c.fullname,c.type";
		$criteria->compare('c.type',$this->type,true);
		$criteria->compare('t.verified',$this->verified,true);
		$criteria->compare('c.fullname',$this->fullname,true);
		$criteria->compare('t.action',$this->action,true);
		$criteria->compare('t.date_created',$this->date_created,true);
		$criteria->join="left join {{customer}} c on t.id_customer=c.id_customer";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_notification DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
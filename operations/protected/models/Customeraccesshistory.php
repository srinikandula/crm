<?php

class Customeraccesshistory extends CActiveRecord
{
	public function tableName()
	{
		return '{{customer_access_history}}';
	}

	public function rules()
	{
		return array(
			array('id_customer,id_admin', 'required'),
			array('message', 'safe'),
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

	public function getAccessHistory($id){ 
		return Yii::app()->db->createCommand('select concat(a.first_name," ",a.last_name,"-",ar.role) as admin,cah.date_created,cah.message from {{admin_role}} ar,{{admin}} a,{{customer_access_history}} cah where cah.id_customer="'.$id.'" and a.id_admin=cah.id_admin and a.id_admin_role=ar.id_admin_role')->queryAll();
	}
}
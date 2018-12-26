<?php

class Notificationbackend extends CActiveRecord
{
	public $admin_name;
	public function tableName()
	{
		return '{{notification_backend}}';
	}

	public function rules()
	{
		return array(
			array('info,sent_to', 'required'),
			array('date_sent,id_admin','safe'),	
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
		$criteria->select="t.*,concat(a.first_name,' ',a.last_name) as admin_name";
		$criteria->compare('t.info',$this->info,true);
		$criteria->compare('t.sent_to',$this->sent_to,true);
		$criteria->compare('t.date_sent',$this->date_sent,true);
		$criteria->compare('concat(a.first_name," ",a.last_name)',$this->id_admin,true);
		/*$criteria->compare('t.date_created',$this->date_created,true);*/
		$criteria->join="left join {{admin}} a on t.id_admin=a.id_admin";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_notification_backend DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
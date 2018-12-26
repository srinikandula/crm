<?php

class AdminLogHistory extends CActiveRecord
{
	public $role;
	public $first_name;
	public $last_name;
	public function tableName()
	{
		return '{{admin_log_history}}';
	}


	public function rules()
	{

		return array(
			array('id_log', 'numerical', 'integerOnly'=>true),
			array('page_accessed', 'length', 'max'=>80),
			array(' page_url', 'length', 'max'=>255),
			array('action, ip_address', 'length', 'max'=>20),
			array('access_date', 'safe'),
			array('id_log, access_date, id_admin, page_accessed,  page_url, action, ip_address', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(	
		'admin'=>array(self::BELONGS_TO,'Admin','id_admin'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'access_date' => 'Access Date',
			'id_admin' => 'Admin',
			'page_accessed' => 'Page Accessed',
			 
			'page_url' =>'Page Url',
			'action' => 'Action',
			'ip_address' => 'Ip Address',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;
	    //$criteria->with=array('admin');
		//$criteria->compare('admin.firstname',$this->admin_role,true);
		$criteria->select='t.*,ar.role,a.first_name,a.last_name';
		/*$criteria->compare('access_date',$this->access_date,true);
		$criteria->compare('id_admin',$this->id_admin);
		$criteria->compare('ar.role',$this->role,true);
		$criteria->compare('page_accessed',$this->page_accessed,true);

		$criteria->compare('page_url',$this->page_url,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('ip_address',$this->ip_address,true);*/
		$criteria->join="LEFT JOIN {{admin}} a ON (a.id_admin=t.id_admin) INNER JOIN {{admin_role}} ar ON (ar.id_admin_role=a.id_admin_role)";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
                'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_log DESC',
			),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

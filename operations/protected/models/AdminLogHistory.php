<?php

class AdminLogHistory extends CActiveRecord
{
	public $role;
	public $first_name;
	public $last_name;
	public $start_date;
	public $end_date;

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
			//array('start_date,end_date,access_date', 'safe'),
			array('role,first_name,last_name,start_date,end_date,id_log, access_date, id_admin, page_accessed,  page_url, action, ip_address', 'safe'),
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
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('ar.role',$this->role,true);
		/*$criteria->compare('id_admin',$this->id_admin);
		$criteria->compare('page_accessed',$this->page_accessed,true);

		$criteria->compare('page_url',$this->page_url,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('ip_address',$this->ip_address,true);*/

		$expDA[0]=$this->start_date;
		$expDA[1]=$this->end_date;
		if($expDA[0]!="" && $expDA[1]==""){
			$criteria->compare('date(access_date)>',$expDA[0]);	
		}else if($expDA[0]=="" && $expDA[1]!=""){
			$criteria->compare('date(access_date)<',$expDA[1]);
		}else if($expDA[0]!="" && $expDA[0]!=""){
			$criteria->compare('date(access_date)>',$expDA[0]);
			$criteria->compare('date(access_date)<',$expDA[1]);
		}

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

	public function getLogHistoryReport(){
		//SELECT id_admin,min(access_date) as min_date,max(access_date) as max_date FROM `eg_admin_log_history` group by date(access_date), id_admin
		//exit($this->start_date." ".$this->end_date);
		$criteria=new CDbCriteria;
	    $criteria->select='t.ip_address,min(t.access_date) as start_date,max(t.access_date) as end_date,a.first_name,a.last_name';
		
		$criteria->compare('a.first_name',$this->first_name,true);
		$criteria->compare('a.last_name',$this->last_name,true);
		//$criteria->compare('date(access_date)',$this->end_date);

		$expDA[0]=$this->start_date;
		$expDA[1]=$this->end_date;
		if($expDA[0]!="" && $expDA[1]==""){
			$criteria->compare('date(access_date)>',$expDA[0]);	
		}else if($expDA[0]=="" && $expDA[1]!=""){
			$criteria->compare('date(access_date)<',$expDA[1]);
		}else if($expDA[0]!="" && $expDA[0]!=""){
			$criteria->compare('date(access_date)>',$expDA[0]);
			$criteria->compare('date(access_date)<',$expDA[1]);
		}
		
		$criteria->join="INNER JOIN {{admin}} a ON (a.id_admin=t.id_admin)";
		$criteria->group="date(t.access_date), t.id_admin";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
                'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'access_date DESC',
			),
		));
			
	}
}

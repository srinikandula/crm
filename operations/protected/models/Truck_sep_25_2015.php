<?php

class Truck extends CActiveRecord
{
	public $fullname;
	public $title;
	public $type;
	public function tableName()
	{
		return '{{truck}}';
	}

	public function rules()
	{
		return array(
			array('id_customer,id_truck,truck_reg_no,id_truck_type,tracking_available,insurance_available,status,make_year,make_month,', 'required'),
			array('status,approved', 'numerical', 'integerOnly'=>true),
			array('description,source_address,source_city,source_state,source_lat,source_lng,fullname,title,driver_driving_licence,vehicle_insurance,fitness_certificate,vehicle_rc','safe'),	
			array('status','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array(
			'description'=>'Vehicle Specifications',
			'id_customer'=>'Customer',
			'truck_reg_no' => 'Truck Registration No',
			'id_truck_type' => 'Truck Type',
			'tracking_available'=>'Tracking Available',
		     
			);
	}


        
    public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->select="c.fullname,c.type,t.id_truck,t.id_customer,t.truck_reg_no,t.tracking_available,t.status,t.approved,tt.title";
		$criteria->compare('t.truck_reg_no',$this->truck_reg_no,true);
		$criteria->compare('c.fullname',$this->fullname,true);
		$criteria->compare('tt.title',$this->title,true);
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		$criteria->compare('t.tracking_available',$this->tracking_available);
		$criteria->join="left join {{customer}} c on c.id_customer=t.id_customer left join {{truck_type}} tt on t.id_truck_type=tt.id_truck_type";
		
		if((int)$_GET['cid']){ //if navigated from customer
			$criteria->condition='t.id_customer="'.$_GET['cid'].'"';
		}
		
		if($_SESSION['id_admin_role']==8){ //for transporter
			$criteria->condition='t.id_customer="'.(int)Yii::app()->user->id.'"';
		}

		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_truck DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
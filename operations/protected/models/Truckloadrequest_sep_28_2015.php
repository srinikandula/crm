<?php

class Truckloadrequest extends CActiveRecord
{
	public $truck_type;
	public $goods_type;
	public $fullname;
	public $idprefix;
	public $mobile;
	public $type;
	public $destinations;
        public $price;
	public $least_quote;
	public $make_year;
	public $make_month;
	public $status;
	public function tableName()
	{
		return '{{truck_load_request}}';
	}

	public function rules()
	{
		return array(
			array('title,id_customer,source_address', 'required'),
			array('id_customer,approved,id_truck_type,tracking_available,id_goods_type,id_truck_type,insurance_available', 'numerical', 'integerOnly'=>true),
			array('truck_type,date_created,mobile,truck_reg_no,fullname,date_available,expected_return,status,make_year,make_month,price,source_state,source_city,source_address,title,add_info,source_lat,source_lng','safe'),
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array(
			'title'=>'Customer',
			'tracking'=>'Tracking Required',
			'insurance'=>'Insurance Required',
			'id_load_type'=>'Load Type',
			'id_truck_type'=>'Truck Type',
			'id_goods_type'=>'Goods Type',
			);
	}


        
    /*public function search()
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
	}*/

	public function searchLoad()
	{

		$criteria=new CDbCriteria;
		$criteria->select="(select group_concat(tlrd.destination_city) from {{truck_load_request_destinations}} tlrd where tlrd.id_truck_load_request=t.id_truck_load_request) as destinations,c.idprefix, c.type,c.fullname,c.mobile,t.*,tt.title as truck_type,gt.title as goods_type";
		$criteria->compare('t.status',$this->status);
		$criteria->compare('c.type',$this->type);
		$criteria->compare('c.fullname',$this->fullname,true);
		$criteria->compare('c.mobile',$this->mobile,true);
		$criteria->compare('t.source_city',$this->source_city,true);
		$criteria->compare('t.make_year',$this->make_year,true);
		$criteria->compare('t.make_month',$this->make_month,true);
		$criteria->compare('t.truck_reg_no',$this->truck_reg_no,true);
		$criteria->compare('t.date_available',$this->date_available,true);
                $criteria->compare('t.expected_return',$this->expected_return,true);
		$criteria->compare('t.date_created',$this->date_created,true);
		$criteria->compare('tt.title',$this->truck_type,true);
		$criteria->compare('t.tracking_available',$this->tracking_available);
		$criteria->compare('t.insurance_available',$this->insurance_available);
		if($_SESSION['id_admin_role']!=1){ //for outbound calling team
			//$criteria->condition='t.id_admin_created="'.(int)Yii::app()->user->id.'"';
			$criteria->compare('t.id_admin_created',(int)Yii::app()->user->id);
		}	
	
		$criteria->join="inner join {{customer}} c on t.id_customer=c.id_customer left join {{truck_type}} tt on t.id_truck_type=tt.id_truck_type left join {{goods_type}} gt on gt.id_goods_type=t.id_goods_type";	
		
		
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_truck_load_request DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
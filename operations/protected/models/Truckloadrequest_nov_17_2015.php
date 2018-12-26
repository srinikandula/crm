<?php

class Truckloadrequest extends CActiveRecord
{
	public $truck_type;
	public $goods_type;
	public $fullname;
	public $idprefix;
	public $mobile;
	public $type;
	public $destination_address;
        public $price;
	public $least_quote;
	public $make_year;
	public $make_month;
	public $status;
	public $date_created_from;
	public $date_created_to;
	public $group_by;
        public $title;
	public function tableName()
	{
		return '{{truck_load_request}}';
	}

	public function rules()
	{
		return array(
			array('title,id_customer,source_address', 'required'),
			array('id_customer,approved,id_truck_type,tracking_available,id_goods_type,id_truck_type,insurance_available', 'numerical', 'integerOnly'=>true),
			array('id_truck_load_request,idprefix,destination_address,truck_type,date_created,mobile,truck_reg_no,fullname,date_available,expected_return,status,make_year,make_month,price,source_state,source_city,source_address,title,add_info,source_lat,source_lng,date_created_from,date_created_to,group_by','safe'),
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
		$criteria->select="tlrd.destination_address,tlrd.price,c.idprefix, c.type,c.fullname,c.mobile,t.*,tt.title as truck_type,gt.title as goods_type";
		$criteria->compare('t.status',$this->status);
                $criteria->compare('t.id_truck_load_request',$this->id_truck_load_request);
		$criteria->compare('c.type',$this->type);
		$criteria->compare('c.idprefix',$this->idprefix);
		$criteria->compare('c.fullname',$this->fullname,true);
		$criteria->compare('tlrd.destination_address',$this->destination_address,true);
		$criteria->compare('tlrd.price',$this->price);
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
		$criteria->compare('t.add_info',$this->add_info,true);
		if(!Yii::app()->getController('Controller')->listAllPerm){ 
			//$criteria->condition='t.id_admin_created="'.(int)Yii::app()->user->id.'"';
			$criteria->compare('t.id_admin_created',(int)Yii::app()->user->id);
		}	
	
		$criteria->join="inner join {{truck_load_request_destinations}} tlrd on  t.id_truck_load_request=tlrd.id_truck_load_request left join {{customer}} c on t.id_customer=c.id_customer left join {{truck_type}} tt on t.id_truck_type=tt.id_truck_type left join {{goods_type}} gt on gt.id_goods_type=t.id_goods_type";	
		
		
		
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

	public function getLoadRequestBlock($input){
		$criteria=new CDbCriteria;
		$criteria->select="group_concat(t.id_load_truck_request) as title,count(t.id_load_truck_request) as id_load_truck_request";
		
		$date_created_from=$this->date_created_from;
		$date_created_to=$this->date_created_to;

		if($date_created_from!="" && $date_created_to==""){
			$criteria->compare('date(t.date_created)>',$date_created_from);	
		}else if($date_created_from=="" && $date_created_to!=""){
			$criteria->compare('date(t.date_created)<',$date_created_to);
		}else if($date_created_from!="" && $date_created_from!=""){
			$criteria->compare('date(t.date_created)>',$date_created_from);
			$criteria->compare('date(t.date_created)<',$date_created_to);
		}
		
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		
		if($this->source_address!=""){
			$criteria->compare('t.source_city',$this->source_address,true,'or');
			$criteria->compare('t.source_state',$this->source_address,true,'or');
			$criteria->compare('t.source_address',$this->source_address,true,'or');
		}

		if($this->destination_address!=""){
			$criteria->compare('t.destination_city',$this->destination_address,true,'or');
			$criteria->compare('t.destination_state',$this->destination_address,true,'or');
			$criteria->compare('t.destination_address',$this->destination_address,true,'or');
		}
		
		switch($input['type']){
				case 'booked_requests':
 					$criteria->condition='t.id_order!=0';
							   break;
				case 'canceled_requests':
 					$criteria->compare('t.status','0');
							   break;
		}


		return Loadtruckrequest::model()->find($criteria);
	}

	public function loadRequestReport()
	{
        $group_by=$this->group_by==""?"week(t.date_created)":$this->group_by."(t.date_created)";    
		$criteria=new CDbCriteria;
		$criteria->select="group_concat(t.id_truck_load_request) as title,count(t.id_truck_load_request) as id_truck_load_request,min(t.date_created) as date_created_from,max(t.date_created) as date_created_to";
		
		$date_created_from=$this->date_created_from;
		$date_created_to=$this->date_created_to;

		if($date_created_from!="" && $date_created_to==""){
			$criteria->compare('date(t.date_created)>',$date_created_from);	
		}else if($date_created_from=="" && $date_created_to!=""){
			$criteria->compare('date(t.date_created)<',$date_created_to);
		}else if($date_created_from!="" && $date_created_from!=""){
			$criteria->compare('date(t.date_created)>',$date_created_from);
			$criteria->compare('date(t.date_created)<',$date_created_to);
		}
		
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		//$criteria->compare('t.status',$this->status);
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		if($this->source_address!=""){
			$criteria->compare('t.source_city',$this->source_address,true,'or');
			$criteria->compare('t.source_state',$this->source_address,true,'or');
			$criteria->compare('t.source_address',$this->source_address,true,'or');
		}

		if($this->destination_address!=""){
			$criteria->join="inner join {{truck_load_request_destinations}} tlrd on t.id_truck_load_request=tlrd.id_truck_load_request and (tlrd.destination_city like '%".$this->destination_address."%' or tlrd.destination_state like '%".$this->destination_address."%' or tlrd.destination_address like '%".$this->destination_address."%')";
			/*$criteria->compare('t.destination_city',$this->destination_address,true,'or');
			$criteria->compare('t.destination_state',$this->destination_address,true,'or');
			$criteria->compare('t.destination_address',$this->destination_address,true,'or');*/
		}

		$criteria->group=$group_by;
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 't.date_created DESC',
				),
		));
	}

	public function getLoadRequestDetails(){
		$criteria=new CDbCriteria;
		$criteria->select="(select concat(idprefix,',',fullname,',',mobile) from {{customer}} c where c.id_customer=t.id_customer) as title,t.add_info,CASE t.status
  WHEN '0' THEN 'Disable'
  WHEN '1' THEN 'Enable'
 END as status,
  CASE t.approved
  WHEN '0' THEN 'Disable'
  WHEN '1' THEN 'Enable'
 END as approved,t.source_address,t.date_available,t.date_created,t.truck_reg_no,t.add_info,tt.title as id_truck_type,gt.title as id_goods_type,(select group_concat(tlrd.destination_address)  from {{truck_load_request_destinations}} tlrd where tlrd.id_truck_load_request=t.id_truck_load_request) as destination_address";
		$criteria->join="left join {{truck_type}} tt on t.id_truck_type=tt.id_truck_type left join {{goods_type}} gt on t.id_goods_type=gt.id_goods_type";
		$criteria->condition="t.id_truck_load_request in (".base64_decode($_GET['data']).")";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 't.date_created DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
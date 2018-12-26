<?php

class Selltruck extends CActiveRecord
{
	public $interested;
	public $customer_fullname;
	public $customer_mobile;
	public $idprefix;
    public function tableName()
	{
		return '{{sell_truck}}';
	}

	public function rules()
	{
		return array(
			array('truck_reg_no,contact_name,contact_mobile','required'),
			array('views,make,interested,id_customer,gps_account_id,id_truck_type,truck_type_title,truck_reg_state,insurance_exp_date,fitness_exp_date,year_of_mfg,odometer,any_accidents,in_finance,expected_price,isactive,status,date_created,date_modifie,truck_front_pic,truck_back_pic,tyres_front_left_pic,tyres_front_right_pic,tyres_back_left_pic,tyres_back_right_pic,other_pic_1,other_pic_1','safe')
			/*array('id_customer,id_truck,truck_reg_no,id_truck_type,tracking_available,insurance_available,status,make_year,make_month,id_truck_attachment_policy', 'required'),
			array('status,approved', 'numerical', 'integerOnly'=>true),
			array('id_customer_truck_attachment_policy,id_truck_attachment_policy,idprefix,description,source_address,source_city,source_state,source_lat,source_lng,fullname,title,front_pic,back_pic,left_pic,right,top_pic,vehicle_insurance,fitness_certificate,vehicle_rc,fitness_certificate_expiry_date,vehicle_insurance_expiry_date,gps_imei_no,gps_mobile_no,mileage,engine_no,chasis_no','safe'),	
			array('status','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),*/
			);
	}

	public function relations()
	{
		return array();
	}

	public function search()
	{

		$criteria=new CDbCriteria;
		$criteria->select="t.*,c.fullname as customer_name,c.mobile as customer_mobile,idprefix,(select count(*) from eg_sell_truck_interested sti where sti.id_sell_truck=t.id_sell_truck) as interested";
		$criteria->compare('t.id_sell_truck',$this->id_sell_truck);
		$criteria->compare('c.fullname',$this->id_customer,true);
		$criteria->compare('c.idprefix',$this->id_customer,true);
		$criteria->compare('c.mobile',$this->id_customer,true);
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		$criteria->compare('t.truck_reg_no',$this->truck_reg_no,true);
		$criteria->compare('t.truck_type_title',$this->truck_type_title,true);
		$criteria->compare('t.contact_name',$this->contact_name,true);
		$criteria->compare('t.contact_mobile',$this->contact_mobile,true);
		$criteria->compare('t.truck_reg_state',$this->truck_reg_state,true);	
		$criteria->compare('t.isactive',$this->isactive,true);
		$criteria->compare('t.date_created',$this->date_created,true);
		$criteria->compare('t.date_modified',$this->date_modified,true);
		$criteria->join="left join eg_customer c on c.id_customer=t.id_customer";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_sell_truck DESC',
				),
		));
	}
	
        public function attributeLabels()
	{
		return array();
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
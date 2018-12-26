<?php

class Selltruckinterested extends CActiveRecord
{
	public $fullname;
	public $mobile;
	public $type;
	public $idprefix;
	public $truck_reg_no;
	public $truck_type_title;
	public $id_truck_type;
	public $contact_name;
	public $contact_mobile;
	public $exptected_price;
	public $truck_expected_price;
    public function tableName()
	{
		return '{{sell_truck_interested}}';
	}

	public function rules()
	{
		return array(
			array('	id_sell_truck', 'required'),
			array('fullname,mobile,type,idprefix,truck_reg_no,id_truck_type,truck_type_title,contact_name,contact_mobile,truck_exptected_price,status,date_created,id_customer,expected_price,gps_account_id', 'safe'),
			/*array('id_customer_truck_attachment_policy,id_truck_attachment_policy,idprefix,description,source_address,source_city,source_state,source_lat,source_lng,fullname,title,front_pic,back_pic,left_pic,right,top_pic,vehicle_insurance,fitness_certificate,vehicle_rc,fitness_certificate_expiry_date,vehicle_insurance_expiry_date,gps_imei_no,gps_mobile_no,mileage,engine_no,chasis_no','safe'),	
			array('status','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),*/
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
		$criteria->select="t.*,c.fullname,c.mobile,c.type,c.idprefix,st.truck_reg_no,st.id_truck_type,st.truck_type_title,st.contact_name,st.contact_mobile,st.expected_price as truck_expteced_price";
		$criteria->compare('t.status',$this->status,true);
		$criteria->compare('t.id_sell_truck',$this->id_sell_truck);
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		$criteria->join="left join eg_sell_truck st on t.id_sell_truck=st.id_sell_truck left join eg_customer c on (c.id_customer=t.id_customer or c.gps_account_id=t.gps_account_id)";
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder'=>'id_sell_truck_interested DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
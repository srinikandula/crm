<?php

class Customerinsurance extends CActiveRecord
{
	public $fullname;
	public $mobile;
	public $gps_account_id;
    public function tableName()
	{
		return '{{customer_insurance}}';
	}

	public function rules()
	{
		return array(
			array('id_customer', 'required'),
			array('status,file,idv,vehicle_number,age,ncb,imt,weight,pa_owner_driver,nil_dep,total_premium,od_rate,od_basic_od_premium,od_gvw_premium,od_total_basic_od_premium,od_elec_fitting,od_bi_fuel_system_premium,od_discount_amount,od_post_disount_amount,od_imt_23,od_post_imt_23_premium,od_ncb_amount,od_total_od_premium,lb_basic_tp_premium,lb_compulsory_owner_driver,lb_paid_drivers_clearners,lb_tp_premium_bi_fuel_system,lb_nfpp_premium,lb_total_liability_premium,lb_gross_premium,lb_service_tax,date_created','safe'),
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array(
			'file'=>'file',
			'idv'=>'IDV (In Rs)',
			'vehicle_number'=>'Vehicl No',
			'age'=>'Vehicle Age',
			'ncb'=>'NCB (in %)',
			'imt'=>'IMT 23 Required',
			'weight'=>'Gross Weight',
			'pa_owner_driver'=>'PA Owner/Driver',
			'nil_dep'=>'Nil Dep',
			'total_premium'=>'Total Premium',
			'od_rate'=>'Rate',
			'od_basic_od_premium'=>'Basic OD',
			'od_gvw_premium'=>'GVW Premium',
			'od_total_basic_od_premium'=>'Total Basic OD',
			'od_elec_fitting'=>'Elec & Elect Fitting',
			'od_bi_fuel_system_premium'=>'Bi-Fuel System',
			'od_discount_amount'=>'Discount Amount',
			'od_post_disount_amount'=>'Post Discount',
			'od_imt_23'=>'IMT 23',
			'od_post_imt_23_premium'=>'Post IMT-23',
			'od_ncb_amount'=>'NCB Amount',
			'od_total_od_premium'=>'Total OD',
			'lb_basic_tp_premium'=>'Basic TP',
			'lb_compulsory_owner_driver'=>'Compulsory Owner/Driver',
			'lb_paid_drivers_clearners'=>'Paid Drivers/Cleaners',
			'lb_tp_premium_bi_fuel_system'=>'TP for Bi-Fuel',
			'lb_nfpp_premium'=>'NFPP Premium',
			'lb_total_liability_premium'=>'Total Liability',
			'lb_gross_premium'=>'Gross Prem',
			'lb_service_tax'=>'Service Tax'
			);
	}


        
    public function search()
	{

		$criteria=new CDbCriteria;
		$criteria->select="c.fullname,c.mobile,c.gps_account_id,t.*,concat(a.first_name,' ',a.last_name) as id_admin";
		$criteria->compare('t.vehicle_number',$this->vehicle_number,true);
		$criteria->compare('t.status',$this->status);
        $criteria->compare('t.date_created',$this->date_created);
		$criteria->join='inner join {{customer}} c on c.id_customer=t.id_customer left join {{admin}} a on t.id_admin=a.id_admin';
		//exit("here");
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_customer_insurance DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
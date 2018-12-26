<?php

class Customerinsurance extends CActiveRecord
{
	
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
			'title' => 'Title',
			'tonnes' => 'Tonnes',
                        'mileage' => 'Mileage',
			'status' => 'Status',
			
			);
	}


        
    public function search()
	{

		$criteria=new CDbCriteria;
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.tonnes',$this->tonnes);
                $criteria->compare('t.mileage',$this->mileage);
		$criteria->compare('t.status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_truck_type DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
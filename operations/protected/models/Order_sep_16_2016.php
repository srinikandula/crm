<?php

class Order extends CActiveRecord
{
 	public function tableName()
	{
		return '{{order}}';
	}

	public function rules()
	{
		return array(
			array('id_source,id_destination,date_ordered,id_customer_ordered,id_customer,amount,id_order_status', 'required'),
			array('id_order,id_source,id_destination,', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('status','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),
			array('date_modified,customer_company,customer_address,customer_city,customer_state,orderperson_fullname,orderperson_mobile,orderperson_email,orderperson_company,orderperson_address,orderperson_city,orderperson_state,
id_truck,truck_reg_no,truck_type,tracking_available,date_available,id_truck_type,insurance_available,id_load_type,id_goods_type,price_from,price_to,id_load_truck_request,commission,insurance,goods_type,load_type','safe'),
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
			'status' => 'Status',
			);
	}


        
    public function search()
	{

		$criteria=new CDbCriteria;
		//$criteria->compare('t.title',$this->title,true);
		//$criteria->compare('t.status',$this->status);
		if($_SESSION['id_admin_role']==8){ //transporter can view only his orders
			$criteria->condition="t.id_customer_ordered=".Yii::app()->user->id;
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_order DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

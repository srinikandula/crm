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
			array('driver_mobile,driver_name,destination_address,source_address,date_modified,customer_company,customer_address,customer_city,customer_state,orderperson_fullname,orderperson_mobile,orderperson_email,orderperson_company,orderperson_address,orderperson_city,orderperson_state,
id_truck,truck_reg_no,truck_type,tracking_available,date_available,id_truck_type,insurance_available,id_load_type,id_goods_type,price_from,price_to,id_load_truck_request,commission,insurance,goods_type,load_type,order_status_name','safe'),
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


	public function Assignment(){
		
		$criteria=new CDbCriteria;
        $criteria->select="t.*";
		$criteria->compare('date_ordered',$this->date_ordered,true);
		$criteria->compare('order_status_name',$this->order_status_name,true);
		/*$criteria->compare('isactive','1');
		$criteria->compare('id_order','0');*/
		//$criteria->condition='type="T"';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
                ),
				'sort' => array(
					'defaultOrder' => 't.date_ordered DESC',
					),
		));
	}

        
    public function search()
	{

		$criteria=new CDbCriteria;
		//$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.id_order',$this->id_order);
        $criteria->compare('date_ordered',$this->date_ordered,true);
		$criteria->compare('date_available',$this->date_available,true);
		$criteria->compare('source_address',$this->source_address,true);
		$criteria->compare('destination_address',$this->destination_address,true);

		$criteria->compare('orderperson_fullname',$this->orderperson_fullname,true);
		$criteria->compare('orderperson_mobile',$this->orderperson_mobile,true);
		$criteria->compare('orderperson_email',$this->orderperson_email,true);
		$criteria->compare('driver_name',$this->driver_name,true);
		$criteria->compare('driver_mobile',$this->driver_mobile,true);
		
		if($_SESSION['id_admin_role']==8){ //transporter can view only his orders
			//$criteria->condition="t.id_customer_ordered=".Yii::app()->user->id;
			$criteria->compare('t.id_customer_ordered',Yii::app()->user->id);
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

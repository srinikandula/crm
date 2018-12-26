<?php

class Order extends CActiveRecord
{
	public $message;
	public $date_available_from;
	public $date_available_to;
	public $date_ordered_from;
	public $date_ordered_to;
    public $title;
	public $transaction;
	public $billing;

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
			array('date_available_from,date_available_to,date_ordered_from,date_ordered_to,customer_fullname,customer_mobile,message,driver_mobile,driver_name,destination_address,source_address,date_modified,customer_company,customer_address,customer_city,customer_state,orderperson_fullname,orderperson_mobile,orderperson_email,orderperson_company,orderperson_address,orderperson_city,orderperson_state,
id_truck,truck_reg_no,truck_type,tracking_available,date_available,id_truck_type,insurance_available,id_load_type,id_goods_type,price_from,price_to,id_load_truck_request,commission,insurance,goods_type,load_type,order_status_name,expenses_diesel,expenses_tollgate,expenses_loading_unloading,expenses_police_charges,truck_source_start_date_time,truck_destination_reach_date_time,truck_route_run_time,transaction,billing','safe'),
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
			'expenses_diesel' => 'Diesel Expenses',
			'expenses_tollgate' => 'Toll Gate Charges',
			'expenses_loading_unloading' => 'Loading/Unloading Charges',
			'expenses_police_charges' => 'Police Charges'
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
		$criteria->select='t.*,(select oh.message from {{order_history}} oh where oh.id_order=t.id_order order by date_created desc limit 1) as message';
		$criteria->compare('t.id_order',$this->id_order);
        $criteria->compare('date_ordered',$this->date_ordered,true);
		
		//if($this->date_available!=""){
			//$expDA=explode(",",$this->date_available);
			$expDA[0]=$this->date_available_from;
			$expDA[1]=$this->date_available_to;
			if($expDA[0]!="" && $expDA[1]==""){
				$criteria->compare('date(date_available)>',$expDA[0]);	
			}else if($expDA[0]=="" && $expDA[1]!=""){
				$criteria->compare('date(date_available)<',$expDA[1]);
			}else if($expDA[0]!="" && $expDA[0]!=""){
				$criteria->compare('date(date_available)>',$expDA[0]);
				$criteria->compare('date(date_available)<',$expDA[1]);
			}
		//}

		//if($this->date_ordered!=""){
		//$expDO=explode(",",$this->date_ordered);
			$expDO[0]=$this->date_ordered_from;
			$expDO[1]=$this->date_ordered_to;
			if($expDO[0]!="" && $expDO[1]==""){
				$criteria->compare('date(date_ordered)>',$expDO[0]);	
			}else if($expDO[0]=="" && $expDO[1]!=""){
				$criteria->compare('date(date_ordered)<',$expDO[1]);
			}else if($expDO[0]!="" && $expDO[0]!=""){
				$criteria->compare('date(date_ordered)>',$expDO[0]);
				$criteria->compare('date(date_ordered)<',$expDO[1]);
			}
		//}
		
		$criteria->compare('date_available',$this->date_available,true);
		$criteria->compare('source_address',$this->source_address,true);
		$criteria->compare('destination_address',$this->destination_address,true);
		//$criteria->compare('order_status_name',$this->order_status_name,true);
		$criteria->compare('id_order_status',$this->id_order_status);
		$criteria->compare('orderperson_fullname',$this->orderperson_fullname,true);
		$criteria->compare('orderperson_mobile',$this->orderperson_mobile,true);
		$criteria->compare('customer_fullname',$this->customer_fullname,true);
		$criteria->compare('customer_mobile',$this->customer_mobile,true);
		$criteria->compare('orderperson_email',$this->orderperson_email,true);
		$criteria->compare('driver_name',$this->driver_name,true);
		$criteria->compare('driver_mobile',$this->driver_mobile,true);
		$criteria->compare('truck_reg_no',$this->truck_reg_no,true);
		
		if($this->message!=""){
			//exit($this->message);
			$criteria->addCondition('id_order in (select oh.id_order from {{order_history}} oh where oh.id_order=t.id_order and oh.message like "%'.$this->message.'%")');
		}
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

	public function getOrderTotal(){
		$criteria=new CDbCriteria;
		$criteria->select='sum(amount) as amount';
		$criteria->compare('t.id_order',$this->id_order);
        $criteria->compare('date_ordered',$this->date_ordered,true);
		
		//if($this->date_available!=""){
			//$expDA=explode(",",$this->date_available);
			$expDA[0]=$this->date_available_from;
			$expDA[1]=$this->date_available_to;
			if($expDA[0]!="" && $expDA[1]==""){
				$criteria->compare('date(date_available)>',$expDA[0]);	
			}else if($expDA[0]=="" && $expDA[1]!=""){
				$criteria->compare('date(date_available)<',$expDA[1]);
			}else if($expDA[0]!="" && $expDA[0]!=""){
				$criteria->compare('date(date_available)>',$expDA[0]);
				$criteria->compare('date(date_available)<',$expDA[1]);
			}
		//}

		//if($this->date_ordered!=""){
		//$expDO=explode(",",$this->date_ordered);
			$expDO[0]=$this->date_ordered_from;
			$expDO[1]=$this->date_ordered_to;
			if($expDO[0]!="" && $expDO[1]==""){
				$criteria->compare('date(date_ordered)>',$expDO[0]);	
			}else if($expDO[0]=="" && $expDO[1]!=""){
				$criteria->compare('date(date_ordered)<',$expDO[1]);
			}else if($expDO[0]!="" && $expDO[0]!=""){
				$criteria->compare('date(date_ordered)>',$expDO[0]);
				$criteria->compare('date(date_ordered)<',$expDO[1]);
			}
		//}
		
		$criteria->compare('date_available',$this->date_available,true);
		$criteria->compare('source_address',$this->source_address,true);
		$criteria->compare('destination_address',$this->destination_address,true);
		//$criteria->compare('order_status_name',$this->order_status_name,true);
		$criteria->compare('id_order_status',$this->id_order_status);
		$criteria->compare('orderperson_fullname',$this->orderperson_fullname,true);
		$criteria->compare('orderperson_mobile',$this->orderperson_mobile,true);
		$criteria->compare('customer_fullname',$this->customer_fullname,true);
		$criteria->compare('customer_mobile',$this->customer_mobile,true);
		$criteria->compare('orderperson_email',$this->orderperson_email,true);
		$criteria->compare('driver_name',$this->driver_name,true);
		$criteria->compare('driver_mobile',$this->driver_mobile,true);
		$criteria->compare('truck_reg_no',$this->truck_reg_no,true);
		
		if($this->message!=""){
			//exit($this->message);
			$criteria->addCondition('id_order in (select oh.id_order from {{order_history}} oh where oh.id_order=t.id_order and oh.message like "%'.$this->message.'%")');
		}
		if($_SESSION['id_admin_role']==8){ //transporter can view only his orders
			//$criteria->condition="t.id_customer_ordered=".Yii::app()->user->id;
			$criteria->compare('t.id_customer_ordered',Yii::app()->user->id);
		}

		$row=Order::model()->find($criteria);
		return $row->amount;
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getOrderpaymentpendingreport($input){
			
			
			$count=Yii::app()->db->createCommand("select count(*) as count from eg_order o where  (o.id_order_status!=2 and o.id_order_status!=6) and (o.date_pod_submitted<=now())")->queryScalar();

			$qry="select o.*,(IFNULL((select sum(amount) from eg_order_billing_history obh where obh.customer_type='L' and o.id_order=obh.id_order and obh.amount_prefix='+' group by amount_prefix),0)-IFNULL((select sum(amount) from eg_order_billing_history obh where obh.customer_type='L' and o.id_order=obh.id_order and obh.amount_prefix='-' group by amount_prefix),0)) as billing,(IFNULL((select sum(amount) from eg_order_transaction_history obh where obh.customer_type='L' and o.id_order=obh.id_order and obh.amount_prefix='+' group by amount_prefix),0)-IFNULL((select sum(amount) from eg_order_transaction_history obh where obh.customer_type='L' and o.id_order=obh.id_order and obh.amount_prefix='-' group by amount_prefix),0)) as transaction from eg_order o where  (o.id_order_status!=2 and o.id_order_status!=6) and (o.date_pod_submitted<=now())";
			$sql=Yii::app()->db->createCommand($qry);
		
		$sqlDataProvider=new CSqlDataProvider($sql, array(
			'totalItemCount'=>$count,
			'sort' => array(
					'defaultOrder' => 'id_order DESC',
					),
			'pagination'=>array(
				'pageSize'=>'50',
			),
		));
		
		return $sqlDataProvider;
	
	}
}
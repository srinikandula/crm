<?php

class Truckloadrequestdestinations extends CActiveRecord
{
    public $price;
	public $destination_city;
	public function tableName()
	{
		return '{{truck_load_request_destinations}}';
	}

	public function rules()
	{
		return array(
			array('id_truck_load_request,destination_address', 'required'),
			array('destination_state,destination_city,destination_address,destination_lat,destination_lng,title,price','safe'),
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
		$criteria->select="c.type,c.fullname,t.*,tt.title as truck_type,gt.title as goods_type";
		$criteria->compare('c.type',$this->type);
		$criteria->compare('c.fullname',$this->fullname,true);
		$criteria->compare('t.source_city',$this->source_city,true);
		$criteria->compare('t.destination_city',$this->destination_city,true);
		$criteria->compare('t.date_required',$this->date_required,true);
		$criteria->compare('t.truck_type',$this->truck_type,true);
		$criteria->compare('t.tracking',$this->tracking);
		$criteria->compare('t.price_from',$this->price_from);
		$criteria->compare('t.price_to',$this->price_to);
	
		$criteria->join="inner join {{customer}} c on t.id_customer=c.id_customer inner join {{truck_type}} tt on t.id_truck_type=tt.id_truck_type inner join {{goods_type}} gt on gt.id_goods_type=t.id_goods_type";	
		
		if((int)$_GET['cid']){ //if navigated from truck
			$criteria->condition='t.id_customer="'.$_GET['cid'].'"';
		}

		if($_SESSION['id_admin_role']==8){ //for transporter
			$criteria->condition='t.id_customer="'.(int)Yii::app()->user->id.'"';
		}

		if($_SESSION['id_admin_role']==10){ //for outbound calling team
			$criteria->condition='t.id_admin_assigned="'.(int)Yii::app()->user->id.'"';
		}
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_load_truck_request DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
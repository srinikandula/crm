<?php

class Loadtruckrequest extends CActiveRecord
{
	public $truck_type;
	public $goods_type;
	public $fullname;
	public $type;
	public $status;
	public $least_quote;
	public $tonnes;
	public $approved;
	public function tableName()
	{
		return '{{load_truck_request}}';
	}

	public function rules()
	{
		return array(
			array('title,id_customer,source_address,destination_address', 'required'),
			array('id_customer,approved,truck_type,tracking,id_goods_type,id_truck_type,insurance,id_load_type', 'numerical', 'integerOnly'=>true),
			array('pickup_point,status,type,fullname,truck_type,goods_type,price_from,price_to,date_requried,date_modified,date_created,destination_state,destination_city,source_state,source_city,source_address,destination_address,title,comment,source_lat,source_lng,destination_lat,destination_lng,tonnes','safe'),
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


	public function Assignment(){
		
		$criteria=new CDbCriteria;
        $criteria->select="t.*";
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('isactive','1');
		$criteria->compare('id_order','0');
		//$criteria->condition='type="T"';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
                ),
				'sort' => array(
					'defaultOrder' => 't.date_created DESC',
					),
		));
	}

	public function searchLoad()
	{

		$criteria=new CDbCriteria;
		$criteria->select="c.type,c.fullname,t.*,tt.title as truck_type,gt.title as goods_type";
		$criteria->compare('t.approved',$this->approved);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('c.fullname',$this->fullname,true);
		$criteria->compare('c.type',$this->type);
		$criteria->compare('t.source_city',$this->source_city,true);
		$criteria->compare('t.destination_city',$this->destination_city,true);
		$criteria->compare('t.tracking',$this->tracking);
		$criteria->compare('t.insurance',$this->insurance);
		$criteria->compare('t.date_created',$this->date_created,true);
		$criteria->compare('date(t.date_required)',$this->date_required,true);
		$criteria->compare('tt.title',$this->truck_type,true);
		
		$criteria->compare('gt.title',$this->goods_type,true);
		$criteria->compare('t.status',$this->status,true);
		
	
		$criteria->join="inner join {{customer}} c on t.id_customer=c.id_customer inner join {{truck_type}} tt on t.id_truck_type=tt.id_truck_type inner join {{goods_type}} gt on gt.id_goods_type=t.id_goods_type";	
		
		if((int)$_GET['cid']){ //if navigated from truck
			//$criteria->condition='t.id_customer="'.$_GET['cid'].'"';
			$criteria->compare('t.id_customer',$_GET['cid']);
		}

		if($_SESSION['id_admin_role']==8){ //for transporter
			//$criteria->condition='t.id_customer="'.(int)Yii::app()->user->id.'"';
			$criteria->compare('t.id_customer',(int)Yii::app()->user->id);
		}

		if($_SESSION['id_admin_role']==10){ //for outbound calling team
			//$criteria->condition='t.id_admin_assigned="'.(int)Yii::app()->user->id.'"';
			$criteria->compare('t.id_admin_assigned',(int)Yii::app()->user->id);
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
<?php

class Ordercustomersupport extends CActiveRecord
{
	public $posts;
	public $dc;
	public $fullname;
 	public function tableName()
	{
		return '{{order_customer_support}}';
	}

	public function rules()
	{
		return array(
			array('id_customer,id_order,details,status', 'required'),
			array('fullname,posts,dc,id_admin,details,status','safe'),
			);
	}

	public function relations()
	{
		return array();
	}
	
    public function attributeLabels()
	{
		return array('id_admin'=>'Admin',
					'id_customer'=>'Customer',
					'id_order'=>'Order Id',
					'details'=>'Message',
		);
	}


        
    public function search()
	{

		$criteria=new CDbCriteria;
		/*$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.status',$this->status);*/
		$criteria->select="t.id_order,t.id_customer,(select fullname from {{customer}} cu where cu.id_customer=t.id_customer ) as fullname,(select status from {{order_customer_support}} c where t.id_customer=c.id_customer order by date_created desc limit 1) as status,(select count(*) as count from eg_order_customer_support c where t.id_customer=c.id_customer and c.id_admin=0) as posts,max(date_created) as dc";
		$criteria->group="t.id_order,t.id_customer";

		//select p.id_order,p.id_customer,(select status from eg_order_customer_support c where p.id_customer=c.id_customer order by date_created desc limit 1) as status,(select count(*) as count from eg_order_customer_support c where p.id_customer=c.id_customer and c.id_admin=0) as posts,max(date_created) as dc from eg_order_customer_support p group by p.id_order,p.id_customer order by dc desc

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'dc DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php
class Customerleadstatushistory extends CActiveRecord
{
	public $zone_search;

	public function tableName()
	{
		return '{{customer_lead_status_history}}';
	}

	public function rules()
	{
		return array(
			array('id_customer,id_admin,status', 'required'),
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
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_goods_type DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
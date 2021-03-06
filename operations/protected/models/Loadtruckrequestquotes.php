
<?php

class Loadtruckrequestquotes extends CActiveRecord
{
    public $idprefix;
	public $mobile;
	public $fullname;
	public function tableName()
	{
		return '{{load_truck_request_quotes}}';
	}

	public function rules()
	{
		return array(
			array('id_load_truck_request,id_admin,id_customer,quote', 'required'),
			array('booking_request,message', 'safe'),
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
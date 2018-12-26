
<?php

class Gpsalerts extends CActiveRecord
{
	public $total;
	public $title;
	public function tableName()
	{
		return '{{gps_alerts}}';
	}

	public function rules()
	{
		return array(
			array('source,destination,id_truck_type,id_goods_type,date_required', 'required'),
                  array('title,sent_count,source,destination,id_truck_type_title,id_truck_type,id_goods_type,id_goods_type_title,price,date_required,sendtoall,message', 'safe'),
            );
	}

	public function relations()
	{
		return array();
	}
	
    public function attributeLabels()
	{
		return array("sendtoall"=>"Send To All","id_truck_type"=>"Truck Type","id_goods_type"=>"Goods Type");
	}
        public function search()
	{
                $criteria=new CDbCriteria;
                $criteria->select="gt.title,t.*,(select count(*) as total from eg_gps_alerts_interested gai where t.id_gps_alerts=gai.id_gps_alert) as total";
				$criteria->compare('source',$this->source,true);
				$criteria->compare('destination',$this->destination,true);
				$criteria->compare('message',$this->message,true);
				$criteria->compare('date_required',$this->date_required,true);
				$criteria->compare('date_created',$this->date_created,true);
				$criteria->compare('sendtoall',$this->sendtoall,true);
				$criteria->compare('gt.title',$this->title,true);
				$criteria->join="left join eg_goods_type gt on t.id_goods_type=gt.id_goods_type";
                return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_gps_alerts DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

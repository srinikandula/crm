
<?php

class Gpstrucklocation extends CActiveRecord
{
    public $truck_type;
    public $name;
    public $mobile_no;
    public function tableName()
	{
		return '{{gps_truck_location}}';
	}

	public function rules()
	{
		return array(
			array('mobile,accountid,truck_reg_no', 'required'),
                    array('address,date_available,title,add_points', 'safe'),
			
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array(
			'truck_reg_no' => 'Truck reg no',
			);
	}


        
    public function search()
	{
        $dd = date("Y-m-d");
        $criteria=new CDbCriteria;
        $criteria->select="t.*,(select fullname from {{customer}} c where t.id_customer= c.id_customer) as name,mobile as mobile_no,(select tt.title from {{truck_type}} tt where tt.id_truck_type=(select tr.id_truck_type from {{truck}} tr where t.truck_reg_no= tr.truck_reg_no)) as truck_type";
        $criteria->compare('truck_reg_no',$this->truck_reg_no,true);
	$criteria->compare('mobile_no',$this->mobile_no,true);
	$criteria->compare('accountid',$this->accountid,true);
        $criteria->compare('address',$this->address,true);
        $criteria->compare('tt.title',$this->truck_type,true);
	$criteria->compare('date_available',$this->date_available,true);
	$criteria->compare('add_points',$this->add_points);
        $criteria->addCondition("date_available >= '$dd'");
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'date_available ASC',
				),
		));
                
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
}

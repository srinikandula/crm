
<?php

class Gpstrucklocation extends CActiveRecord
{
	public function tableName()
	{
		return '{{gps_truck_location}}';
	}

	public function rules()
	{
		return array(
			array('mobile,accountid,truck_reg_no', 'required'),
                    array('address,date_available', 'safe'),
			
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
		$criteria->compare('truck_reg_no',$this->truck_reg_no,true);
                $criteria->compare('address',$this->address,true);
		$criteria->compare('date_available',$this->date_available,true);
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

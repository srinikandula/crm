
<?php

class Tollgateinfo extends CActiveRecord
{
	public function tableName()
	{
		return '{{toll_gate_info}}';
	}

	public function rules()
	{
		return array(
			array('source_city,destination_city', 'required'),
			array('no_of_tollgates,amount,source_state,destination_state,source_lat,source_lng,destination_lat,destination_lng,source_address,destination_address', 'safe'),
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array(
			'amount' => 'Amount',
			);
	}


        
    public function search()
	{

		$criteria=new CDbCriteria;
		$criteria->compare('source_city',$this->source_city,true);
		$criteria->compare('destination_city',$this->destination_city,true);
                $criteria->compare('amount',$this->amount,true);
                $criteria->compare('no_of_tollgates',$this->no_of_tollgates,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_toll_gate_info DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
}
